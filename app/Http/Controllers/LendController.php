<?php

namespace App\Http\Controllers;

use App\Lend;
use App\User;
use App\Borrow;
use App\Account;
use App\SMS;
use App\Transaction;
use Validator;
use Auth;
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Http\Request;

class LendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

   
    public function Lend(Request $request)
    {
        /* Validation */
        $validator = Validator::make($request->all(),[
            "borrowerId" =>"required | numeric",
            "amountLend" =>"required | numeric",
            "borrowId"=>"required | numeric",
        ]);
        if($validator->fails()){
            return $validator->errors();
        }
        $username = 'cagimu'; 
        $apiKey   = '4364fea1f320e7d417614fc23bd4f8bc312268e29b1cf000c45c0cc0772036eb'; 
        $borrower = User::where('id',$request->borrowerId)->first();
        $borrow = Borrow::where('id',$request->borrowId)->first();
        
        $accountToWithdraw = Account::where('CustomerID',Auth::user()->id)->first();
        $amountToLend = $request->amountLend;
        $balance = $borrow->balance;
        $myBalance = $accountToWithdraw->CurrentBalance;
        if($amountToLend>$myBalance){
            return response()->json([
                "status" => "false",
                "error" => "Your account balance is low.Please deposit and try again.",
            ]);
        }
        if($balance == 0){
            $borrow->status = 0;
            if($borrow->save()){
                return response()->json([
                    'status' => "true",
                    "success"=>"Loan fully funded please select another another loan for funding",
                ]);
            }
            return response()->json([
                'status' => "true",
                "success"=>"Loan fully funded please select another another loan for funding",
            ]);
        }
        if($amountToLend > $balance){
            $amountToLend = $balance;
        }

        $accountBal = $accountToWithdraw->CurrentBalance-$amountToLend;
        $accountToWithdraw->CurrentBalance =  $accountBal;
        $re = $accountToWithdraw->save();
        if($re){
            // send sms lender
          $message1 = "Dear ".Auth::user()->FirstName." ".Auth::user()->LastName.", confirmed you have funded KES ".$amountToLend." to ". $borrower->FirstName." ". $borrower->LastName.".";
          $no = Auth::user()->PhoneNumber;
          $new = substr($no,1);
          $phone = '+254'.$new;
          
            $AT = new AfricasTalking($username, $apiKey);

            // Get one of the services
            $sms = $AT->sms();

            // Use the service
            try {
                $result   = $sms->send([
                    'to'      => $phone,
                    'message' => $message1
                ]);
                $data =$result['data'];
                $smsdata =get_object_vars($data);
                $SMSMessageData = $smsdata['SMSMessageData'];
                $msgData = get_object_vars($SMSMessageData);
                $Recipients = $msgData['Recipients'];
                $rcpt = get_object_vars($Recipients[0]);
                $dirtyCost = $rcpt['cost'];
                $cost =substr($dirtyCost,4);
                if($result['status']=="success"){
                    $mySms = new SMS;
                    $mySms->userid = Auth::user()->id;
                    $mySms->Message = $message1;
                    $mySms->phone = $rcpt['number'];
                    $mySms->amount = $cost;
                    $mySms->save();
                }
             } catch (\Throwable $th) {
                //throw $th;
            }
           
            //$sms End
            $accountToDeposit = Account::where('CustomerID',$borrower->id)->first();
            $userBal = $accountToDeposit->CurrentBalance;
            $accountToDeposit->CurrentBalance =  $accountToDeposit->CurrentBalance + $amountToLend;
            if($accountToDeposit->save()){
                $borrow->balance = $borrow->balance - $amountToLend;
                 if($borrow->save()){
                     $lend = new Lend;
                     $lend->borrowerId = $borrower->id;
                     $lend->borroweId = $borrow->id;
                     $lend->userId = Auth::user()->id;
                     $lend->transcationId = rand(100000,999999);
                     $lend->amountLend = $amountToLend;
                      if($lend->save()){
                          //send sms to borrower
                          $message2 = "Dear ".$borrower->FirstName. " " . $borrower->LastName.", your loan has been funded with KES ".$amountToLend.". Check in your mbanking application for more details.";
                          $no = $borrower->PhoneNumber;
                            $new = substr($no,1);
                            $phone2 = '+254'.$new;
                           
                                $AT       = new AfricasTalking($username, $apiKey);

                                // Get one of the services
                                $sms      = $AT->sms();

                                // Use the service
                                try {
                                    $result   = $sms->send([
                                        'to'      => $phone2,
                                        'message' => $message2
                                    ]);
                                    $data =$result['data'];
                                    $smsdata =get_object_vars($data);
                                    $SMSMessageData = $smsdata['SMSMessageData'];
                                    $msgData = get_object_vars($SMSMessageData);
                                    $Recipients = $msgData['Recipients'];
                                    $rcpt = get_object_vars($Recipients[0]);
                                    $dirtyCost = $rcpt['cost'];
                                    $cost =substr($dirtyCost,4);
                                
                                    if($result['status']=="success"){
                                        $mySms = new SMS;
                                        $mySms->userid = Auth::user()->id;
                                        $mySms->Message = $message2;
                                        $mySms->phone = $rcpt['number'];
                                        $mySms->amount = $cost;
                                        $mySms->save();
                                }
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                            // $data = json_encode($result);
                                

                          //endsms
                            $transaction = new Transaction;
                            $transaction->TransactionType = "Fund Loan to ". $accountToDeposit->AccountName;
                            $transaction->TransID = rand(100000,999999);
                            $transaction->TransAmount = $amountToLend;
                            $transaction->UserId = Auth::user()->id;
                            $transaction->AccountNumber = $accountToWithdraw->AccountNumber;
                            $transaction->MSISDN = Auth::user()->PhoneNumber;
                            $transaction->FirstName = Auth::user()->FirstName;
                            $transaction->MiddleName = Auth::user()->MiddleName;
                            $transaction->LastName = Auth::user()->LastName;
                            $transaction->OrgAccountBalance = $myBalance;
                            $transaction->CrtAccountBalance = $myBalance - $amountToLend;
                            if($transaction->save()){  
                                $transaction = new Transaction;
                                $transaction->TransactionType = "Recieved Loan fund from ". $accountToWithdraw->AccountName;
                                $transaction->TransID = rand(100000,999999);
                                $transaction->TransAmount = $amountToLend;
                                $transaction->UserId = $accountToDeposit->CustomerID;
                                $transaction->AccountNumber = $accountToDeposit->AccountNumber;
                                $transaction->MSISDN = $borrower->PhoneNumber;
                                $transaction->FirstName = $borrower->FirstName;
                                $transaction->MiddleName = $borrower->MiddleName;
                                $transaction->LastName = $borrower->LastName;
                                $transaction->OrgAccountBalance = $userBal;
                                $transaction->CrtAccountBalance = $userBal + $amountToLend;
                                if($transaction->save()){
                                    return response()->json([
                                        'status'=>"true",
                                        'success'=>"Your funding was processed successifully."
                                    ]);
                                }
                }

                          
                        }
         
                 }

            }
        }
    }
    
   
    
    public function transferCash(Request $request)
    {
        /* Validation */
        $validator = Validator::make($request->all(),[
            'amount' => 'required | numeric', 
            'accountId'=>'required',
            'accountNumber' => 'required',
        
        ]);
        if ($validator->fails()){
            return $validator->errors();
        }
        $accountToDeposit = Account::where([['AccountNumber','=',$request->accountNumber]])->first();
        if($accountToDeposit ===  null){
            return response()->json([
                'status'=>'false',
                'error'=>'Sorry, the account number you provided is not valid. Please try again. !!!',
            ]);
        }
        $accountToWithdraw = Account::where([['CustomerID','=',Auth::user()->id],['id','=',$request->accountId]])->first();
        $amountToTransfer = $request->amount;
        $myBalance = $accountToWithdraw->CurrentBalance;
        if($amountToTransfer > $myBalance){
            return response()->json([
                'status'=>'false',
                'error'=>'Sorry, you have insufficient fund to complete the transaction. Please top up and try again !!!',
            ]);
        }
        $accountToWithdraw->CurrentBalance = $myBalance - $amountToTransfer;
        if($accountToWithdraw->save()){
            $accountToDeposit = Account::where([['AccountNumber','=',$request->accountNumber]])->first();
            $user = User::where('id',$accountToDeposit->CustomerID)->first();
            $userBal = $accountToWithdraw->CurrentBalance;
            $accountToDeposit->CurrentBalance = $accountToDeposit->CurrentBalance + $amountToTransfer;
            if($accountToDeposit->save()){
                $transaction = new Transaction;
                $transaction->TransactionType = "Tranfer to ". $accountToDeposit->AccountName;
                $transaction->TransID = rand(100000,999999);
                $transaction->TransAmount = $amountToTransfer;
                $transaction->UserId = Auth::user()->id;
                $transaction->AccountNumber = $accountToWithdraw->AccountNumber;
                $transaction->MSISDN = Auth::user()->PhoneNumber;
                $transaction->FirstName = Auth::user()->FirstName;
                $transaction->MiddleName = Auth::user()->MiddleName;
                $transaction->LastName = Auth::user()->LastName;
                $transaction->OrgAccountBalance = $myBalance;
                $transaction->CrtAccountBalance = $myBalance - $amountToTransfer;
                if($transaction->save()){  
                    $transaction = new Transaction;
                    $transaction->TransactionType = "Recieved from ". $accountToWithdraw->AccountName;
                    $transaction->TransID = rand(100000,999999);
                    $transaction->TransAmount = $amountToTransfer;
                    $transaction->UserId = $accountToDeposit->CustomerID;
                    $transaction->AccountNumber = $accountToDeposit->AccountNumber;
                    $transaction->MSISDN = $user->PhoneNumber;
                    $transaction->FirstName = $user->FirstName;
                    $transaction->MiddleName = $user->MiddleName;
                    $transaction->LastName = $user->LastName;
                    $transaction->OrgAccountBalance = $userBal;
                    $transaction->CrtAccountBalance = $userBal + $amountToTransfer;
                    if($transaction->save()){
                        return response()->json([
                            'status'=>'true',
                            'success'=>'The transfer was posted successifully !!!',
                        ]);
                    }
                }
            }

        }
        return response()->json([
            'status'=>'false',
            'success'=>'Transfer could not be processed fo now. Try again later !!!',
        ]);
    }

    public function myLends(){
        $lends = Lend::where('userId',auth('api')->user()->id)->latest()->get();
        return $lends;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function edit(Lend $lend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lend $lend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lend $lend)
    {
        //
    }
}
