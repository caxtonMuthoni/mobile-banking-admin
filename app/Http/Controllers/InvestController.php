<?php

namespace App\Http\Controllers;

use App\Invest;
use App\Account;
use App\TerminateRequest;
use DateTime;
use App\User;
use App\Http\Controllers\TransactionController;
use App\Http\Resources\InvestResource;
use Illuminate\Http\Request;

class InvestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $investments = Invest::all();

        return InvestResource::collection($investments);
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
            "amount" => 'required | numeric | min:5000',
            "duration" => 'required | numeric | min:1'
        ]);

        $amount = $request->amount;
        $duration = $request->duration;

        $user = auth('api')->user();
        $account = Account::where([['CustomerID',$user->id],['AccountNumber',$user->NationalID]])->first();
        if($account != null){
            if($account->CurrentBalance < $amount){
                return response()->json([
                    'status' => false,
                    'message' => "You have insufficient funds to complete transaction."
                ]);
            }else{
               $account->CurrentBalance -= $amount;
               if($account->save()){
                $data = [
                    'TransactionType' => 'invest',
                    'TransactionDescription' => "cash investment",
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
                $t = $transaction->transact($data);
                if($t != null){
                    // Getting interest and calculating  total pay at the end of the duration.
                    $interest = 3;
                    $totalPay =  $amount + ( ($interest/100) * $duration * $amount);
                    //  Get termination date.
                    $today = new DateTime('now');
                    $today->modify('+'.$duration.' months');
                    $terminationDate = $today->format('Y.m.d h.i.s');

                    // Creating new investment and saving it.
                    $investment = new Invest;
                    $investment->userId = $user->id;
                    $investment->accountId = $account->id;
                    $investment->duration = $duration;
                    $investment->interest = $interest;
                    $investment->amount = $amount;
                    $investment->terminationDate = $terminationDate;
                    $investment->totalPay = $totalPay;
                    if($investment->save()){
                        return response()->json([
                            "status" => true,
                            "message" => "Your investment  was processed successfully."
                        ]);
                    }
                }
               } 
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => "please try again later."
            ]);
        }

    }

    /* ================= Get User active investments ================= */

    public function getUserActiveInvestmnets(){
        $userId =  auth('api')->user()->id;
        $investments = Invest::where([['userId',$userId],['status',true]])->latest()->get();
        return InvestResource::collection($investments);
    }

    /* =============================== Reason for investment termination =============================== */
    public function requestTermination(Request $request){
        // validation
        $this->validate($request,[
            'reason' => 'required',
            'investmentId' => 'required'
        ]);

        $termination =  new TerminateRequest;
        $termination->investmentId = $request->investmentId;
        $termination->reason = $request->reason;
        $termination->userId = auth('api')->user()->id;

        if($termination->save()){
            return response()->json([
                'status' => true,
                'message' => 'Request submitted successfully.'
            ]);
        }
      
    }

    /* ========================== User termination requests ========================== */
    public function myTerminationRequests(){
        $userId = auth('api')->user()->id;
        $requests =  TerminateRequest::where('userId',$userId)->latest()->get()->take(10);
        return $requests;
    }
    /* Terminate investment */
    public function terminate(Request $request)
    {
        //validation
        $this->validate($request,[
            'investmentId' => 'required | numeric'
        ]);

        $investment =  Invest::find($request->investmentId);
        $amount = $investment->amount;
        $interest = $investment->interest;
        $created_at = $investment->created_at;

        $account  = Account::find($investment->accountId);
        $user = User::find($investment->userId);

        $today = new DateTime('now');
        $startDate = new DateTime($created_at);
        $dateDiff = round(($startDate->diff($today)->days)/30,2);

        if($dateDiff < $investment->duration){
            $totalAmount =  ($amount + (($interest/100) * $amount * $dateDiff)/ 2) ;
            $investment->totalPay = $totalAmount;
            $investment->terminationDate = $today;
            $investment->status = false;

            if($investment->save()){
                $account->CurrentBalance += $totalAmount;
                if($account->save()){
                    $data = [
                        'TransactionType' => 'invest',
                        'TransactionDescription' => "investment total returns",
                        'TransID' => random_int(100000,10000000000),
                        'UserId' => $account->CustomerID,
                        'AccountNumber' => $account->AccountNumber,
                        'MSISDN' => $user->PhoneNumber,
                        'FirstName' => $user->FirstName,
                        'MiddleName' => $user->MiddleName,
                        'LastName' => $user->LastName,
                        'TransAmount' => $totalAmount,
                        'OrgAccountBalance' => $account->CurrentBalance - $totalAmount,
                        'CrtAccountBalance' => $account->CurrentBalance,
                    ];

                    $transaction =  new TransactionController;
                    $t = $transaction->transact($data);

                    return response()->json([
                        'status' => true,
                        'message' => "Investment terminated successfully."
                    ]);
                }
               
    
                
                
            }
        }



       
    }

    // User investments
    public function myInvestments()
    {
        $userId = auth('api')->user()->id;
        $investments = Invest::where('userId',$userId)->get();
        return InvestResource::collection($investments);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invest $invest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invest $invest)
    {
        //
    }
}
