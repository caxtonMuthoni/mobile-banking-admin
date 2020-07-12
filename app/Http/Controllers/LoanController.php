<?php

namespace App\Http\Controllers;

use App\Loan;
use Illuminate\Http\Request;
use App\LoanType;
use App\Shares;
use DateTime;
use App\Http\Resources\LoanResource;
use App\Http\Resources\TransactionCollection;
use App\Guarantor;
use App\LoanPayment;
use App\Transaction;
use App\Account;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LoanResource::collection(Loan::latest()->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validation
        $this->validate($request,[
            'loanType' => 'required',
            'borrowedAmount' => 'required',
            'period' => 'required | numeric | min:1',
        ]);
        $user = auth('api')->user();
        $outstandingLoans =  Loan::where([['userId','=',$user->id],['status','!=','complete']])->get()->count();
        $loanType = LoanType::where('id',$request->loanType)->first();

        // Check User Account active months for loan qualifications

        $openDate = new DateTime($user->created_at);
        $today = new DateTime('now');

        $diff = $today->diff($openDate)->days;

        if($diff < 100){
            return response()->json([
                'status' => false,
                'message' => 'Sorry you must have used your account for atleast 6 months !!!'
            ]);
        }
        

        //  Does the user qualify for the loan
        //    Get Shares
          $shares =  Shares::where('userId',$user->id)->first();
          if($shares == null){
              return response()->json([
                  'status' => false,
                  'message' => 'Sorry you have insufficient shares !!!'
              ]);
          } else{
            //   compare borrowed amount with shares and diposit contribution
              if($shares->depositContribution < 5000){
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry you must complete the deposit contribution payment !!! Bal:'.(5000-$shares->depositContribution),
                ]);
              }elseif($shares->shares * 3 < $request->borrowedAmount){
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, you qualify for  KSH '.($shares->shares * 3).' and below.',
                ]);
              }
            //   Does the user has an outstanding loan
            elseif($outstandingLoans > 0){
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, you have an outstanding loan',
                ]);
            }
            elseif ($request->borrowedAmount > $loanType->maxAmount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, you can only apply for loans below KSH '.($loanType->maxAmount + 1),
                ]);
            }
            else {
                //Loan calculation
                $borrowedAmount = $request->borrowedAmount;
                $period = $request->period;
                $rate = $loanType->interest;
                $si = ($request->borrowedAmount * $period * $rate)/100;
                $totalRepayable = round(($borrowedAmount + $si),2);
                $installment = round(($totalRepayable/$period),2);

                $guarantorAmount = $totalRepayable - $shares->shares;
                $guaranteedAmount  = 0;
                $guaranteeStatus = false;
                /* TODO--------==== Guarantor checking  and transfer to update */
                $userActiveGuaranted = Guarantor::where([['guarantor','=',$user->id],['status','=',true]])->get();
                if($guarantorAmount <= 0 && $userActiveGuaranted->count() <= 5){
                    $guarantorAmount = $totalRepayable;
                    $guaranteedAmount = $totalRepayable;
                    $guaranteeStatus = true;
                }
                
                //Due Date calculation
                $date = new DateTime('now'); // get Current Date
                $date->modify('+'.$period.' month'); // add months to current day
                $dueDate = $date->format('Y-m-d h:i:s');

                //next Payment Date calculation
                $date = new DateTime('now'); // get Current Date
                $date->modify('+1 month'); // add months to current day
                $nextPayment = $date->format('Y-m-d h:i:s');
                
                // Loan instance
                $loan = new Loan;
                $loan->loanId = random_int(10000,100000000);
                $loan->userId = $user->id;
                $loan->loanType = $loanType->type;
                $loan->borrowedAmount = $borrowedAmount;
                $loan->interest = $rate;
                $loan->period = $period;
                $loan->installment = $installment;
                $loan->dueDate = $dueDate;
                $loan->nextPayment = $nextPayment;
                $loan->totalRepayable = $totalRepayable;
                $loan->guarantorAmount = $guarantorAmount;
                $loan->guaranteedAmount = $guaranteedAmount;
                $loan->guaranteeStatus = $guaranteeStatus;
                if($loan->save()){
                    if($guaranteeStatus){
                        $guarantor = new Guarantor;
                        $guarantor->amount = $totalRepayable;
                        $guarantor->loanId = $loan->id;
                        $guarantor->guarantor = $user->id;
                        $guarantor->save();
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'Loan Posted Successfully. Contact your guarantors to guarantee the loan.',
                    ]);
                }else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sorry, An error Occurred !!!',
                    ]);
                }

            }
          }

    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::find($id);
        return  new LoanResource($loan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $loan = Loan::find($id);
        //validation
        $this->validate($request,[
            'loanType' => 'required',
            'borrowedAmount' => 'required',
            'period' => 'required | numeric | min:1',
        ]);
        $user = auth('api')->user();
        $outstandingLoans =  Loan::where([['userId','=',$user->id],['status','!=','complete']])->get()->count();
        $loanType = LoanType::where('id',$request->loanType)->first();
        //  Does the user qualify for the loan
        //    Get Shares
         if($loan->isProcessed){
            return response()->json([
                'status' => false,
                'message' => 'Sorry you can\'t change a processed Loan !!!'
            ]);
         }
          $shares =  Shares::where('userId',$user->id)->first();
          if($shares == null){
              return response()->json([
                  'status' => false,
                  'message' => 'Sorry you have insufficient shares !!!'
              ]);
          } else{
            //   compare borrowed amount with shares and diposit contribution
              if($shares->depositContribution < 5000){
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry you must complete the deposit contribution payment !!! Bal:'.(5000-$shares->depositContribution),
                ]);
              }elseif($shares->shares * 3 < $request->borrowedAmount){
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, you qualify for  KSH '.($shares->shares * 3).' and below.',
                ]);
              }
            
            elseif ($request->borrowedAmount > $loanType->maxAmount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, you can only apply for loans below KSH '.($loanType->maxAmount + 1),
                ]);
            }
            else {
                //Loan calculation
                $borrowedAmount = $request->borrowedAmount;
                $period = $request->period;
                $rate = $loanType->interest;
                $si = ($request->borrowedAmount * $period * $rate)/100;
                $totalRepayable = round(($borrowedAmount + $si),2);
                $installment = round(($totalRepayable/$period),2);

                $guarantorAmount = $totalRepayable - $shares->shares;
                $guaranteedAmount  = 0;
                $guaranteeStatus = false;
                /*--------==== Guarantor checking  and transfer to update */
                $userActiveGuaranted = Guarantor::where([['guarantor','=',$user->id],['status','=',true]])->get();
                if($guarantorAmount <= 0 && $userActiveGuaranted->count() <= 5){
                    $guarantorAmount = $totalRepayable;
                    $guaranteedAmount = $totalRepayable;
                    $guaranteeStatus = true;
                }

                 //Due Date calculation
                 $date = new DateTime('now'); // get Current Date
                 $date->modify('+'.$period.' month'); // add months to current day
                 $dueDate = $date->format('Y-m-d h:i:s');

                // Loan instance
                
                 // Loan instance
                 $loan = Loan::find($id);
                 $loan->userId = $user->id;
                 $loan->loanType = $loanType->type;
                 $loan->borrowedAmount = $borrowedAmount;
                 $loan->interest = $rate;
                 $loan->period = $period;
                 $loan->installment = $installment;
                 $loan->dueDate = $dueDate;
                 $loan->totalRepayable = $totalRepayable;
                 $loan->guarantorAmount = $guarantorAmount;
                 $loan->guaranteedAmount = $guaranteedAmount;
                 $loan->guaranteeStatus = $guaranteeStatus;
                 $myguarantor = Guarantor::where([['loanId',$loan->id],['guarantor',$user->id]])->first();
                 if($loan->save()){
                     if($guaranteeStatus){
                        if($myguarantor != null){
                            $myguarantor->delete();
                        }
                         $guarantor = new Guarantor;
                         $guarantor->amount = $totalRepayable;
                         $guarantor->loanId = $loan->id;
                         $guarantor->guarantor = $user->id;
                         $guarantor->save();
                     }
                     if( !$guaranteeStatus){
                         
                         if($myguarantor != null){
                             $myguarantor->delete();
                         }
                     }
                    return response()->json([
                        'status' => true,
                        'message' => 'Loan updated Successfully. Contact your guarantors to guarantee the loan.',
                    ]);
                }else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Sorry, An error Occurred !!!',
                    ]);
                }

            }
          }
    }

    public function loanStatement($id){
        $loanPayments = LoanPayment::where('loanId',$id)->latest()->get();
        $transactions = [];
        foreach ($loanPayments as $loanPayment) {
            // add all transactions to  transactions array
            array_push($transactions, new TransactionCollection(Transaction::find($loanPayment->transactionId)));
        }

        return  $transactions;
    }

    public function userLoans($id = 0){
        if($id == 0){
            $id = auth('api')->user()->id;
        }
        $loans = Loan::where('userId',$id)->latest()->get();
        return LoanResource::collection($loans);
    }

    /* ============================Get user loan aliases============================ */
    public function getAlias($id){
        $loan = Loan::find($id);
        $starDate = new DateTime($loan->created_at);
        $nextPayment = new DateTime($loan->nextPayment);
        $today =  new DateTime('now');
        $installment = $loan->installment;
        $paidAmount = $loan->paidAmount;

        $months = round(($today->diff($starDate)->days));
        
        $totalPayment = $months * $installment; // What should have paid
         if($loan->status == 'complete'){
             return 'complete';
         }

        if(($paidAmount - $totalPayment) < 0){
            return round($paidAmount - $totalPayment,2) *-1;
        }
        if(($paidAmount - $totalPayment) == 0){
            return 0;
        }
        return round($paidAmount - $totalPayment,2) * -1;
        

        
    }

    public function defaultingLoans(){
        $defaultingLoan = [];
        $loans = Loan::where([['status','!=','complete']])->latest()->get();
        foreach( $loans as $loan){
            $alias = LoanController::getAlias($loan->id);

            if($alias >= ($loan->installment * 2)){
                array_push($defaultingLoan,new LoanResource($loan));
                if($loan->status != 'defaulting'){
                    $loan->status = 'defaulting';
                    $loan->save();
                }
            }
            if($alias >= ($loan->installment * 4)){
                if($loan->status != 'defaulted'){
                    $loan->status = 'defaulted';
                    $loan->save();
                }
            }
            
        }


        return $defaultingLoan;
    }


    public function loanProcess($value){
        switch ($value) {
            case 'processed':
                $loans = Loan::where('isProcessed',true)->latest()->get();
                break;

            case 'unprocessed':
                $loans = Loan::where('isProcessed',false)->latest()->get();
                break;
            case 'readytoprocess':
                $loans = Loan::where([['isProcessed',false],['guaranteeStatus',true]])->latest()->get();
                break;
            case 'active':
                $loans = Loan::where('status','active')->latest()->get();
                break;
        
            default:
               $loans = Loan::all();
                break;
        }

        return $loans;

        //return LoanResource::collection($loans);
    }

    /* ================== Get user active Loan details ==================  */
    public function getUserActiveLoanDetail(){
        // 1. Get Current user
        $userId = auth('api')->user()->id;
        $id=$loanStatus=$limit=$balance=$aliases = 0;

        // 2. Get user current active loan
        $loan = Loan::where([['status','!=','complete'],['userId',$userId]])->first();
        if($loan != null){
            $id = $loan->id;
            $balance = $loan->totalRepayable - $loan->paidAmount;
            $aliases = LoanController::getAlias($loan->id);
            $loanStatus = $loan->status;
        }

        // 3. Get user shares to determine limit
        $shares = Shares::where('userId',$userId)->value('shares');
        if($shares != null){
            $limit = $shares * 3;
        }
        // 4. return the response
        return response()->json([
            'id' => $id,
            'balance' => $balance,
            'limit' => $limit,
            'alias' => $aliases,
            'loan_status' => $loanStatus
        ]);
    }
    public function payLoan(Request $request){
        // validation
      $this->validate($request,[
          'id' => 'required',
          'amount' => 'required | numeric'
      ]);

      $loanId = $request->id;
      $amount = $request->amount;

      $user = auth('api')->user();
      $loan = Loan::find($loanId);
      $account = Account::where('CustomerID',$user->id)->first();
      $loanBalance = $loan->totalRepayable - $loan->paidAmount;
      $installment = $loan->installment;

      if($account->CurrentBalance < $amount){
          return response()->json([
             'status' => false,
             'message' => "You have insufficient funds to complete transaction."
          ]);
      }else {
          if($amount > $loanBalance){
              $amount = $loanBalance;
          }
          if(($amount + $loan->paidAmount) == $loan->totalRepayable){
              $loan->status = "complete";
          }

          $paidMonths = round(($amount/$installment));

          $currentPaymentDay = new DateTime($loan->nextPayment);
          $currentPaymentDay->modify('+'.$paidMonths.' months');
          $nextPayment = $currentPaymentDay->format('Y-m-d h:i:s');

          $account->CurrentBalance -= $amount;
          if($account->save()){
            $request = [
                'TransactionType' => 'transfer',
                'TransactionDescription' => "Loan repayment",
                'TransID' => random_int(100000,10000000000),
                'UserId' => $account->CustomerID,
                'AccountNumber' => $account->AccountNumber,
                'MSISDN' => $user->PhoneNumber,
                'FirstName' => $user->FirstName,
                'MiddleName' => $user->MiddleName,
                'LastName' => $user->LastName,
                'TransAmount' => $amount,
                'OrgAccountBalance' => $account->CurrentBalance + $amount,
                'CrtAccountBalance' => $account->CurrentBalance,
            ];

            $transaction =  new TransactionController;
            $t = $transaction->transact($request);
            $loanPayment = new LoanPayment;
            $loanPayment->loanId = $loan->id;
            $loanPayment->transactionId = $t->id;
            $loanPayment->save();

            $loan->nextPayment = $nextPayment;
            $loan->paidAmount += $amount;
           if( $loan->save()){
            return  response()->json([
                'status' => true,
                'message' => "Payment was completed successfully.",
            ]);
           }

            
          }

          

      }

    }







    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Loan $loan)
    {
        //
    }
}
