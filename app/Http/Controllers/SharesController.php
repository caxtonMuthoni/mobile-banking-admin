<?php

namespace App\Http\Controllers;

use App\Shares;
use Illuminate\Http\Request;
use App\Account;

class SharesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Shares::all();
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
        $userId = auth('api')->user()->id;
        $account = Account::where([['CustomerID','=',$userId],['AccountCode', 200]])->first();
        $accountId = $account->id;
        $usershare = Shares::where('userId',$userId)->get()->count();
        if($usershare > 0){
            return response()->json([
                'status' => false,
                'message' => 'Sorry, You already have a shares account.'
            ]); 
        }
        $share = new Shares;
        $share->userId = $userId;
        $share->shares = 0;
        $share->depositContribution = 0;
        $share->accountId = $accountId;
        if($share->save()){
            return response()->json([
                'status' => true,
                'message' => 'CONGRATS, you have successfully created a shares account.'
            ]);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'sorry, an error occurred !!!'
            ]);
        }
    }

    public function deposit(Request $request)
    {
        // Validation
        $this->validate($request,[
           'amount' => 'required | numeric | min : 100'
        ]);

        $userId = auth('api')->user()->id;
        $amount = $request->amount;
        $account = Account::where([['CustomerID','=',$userId],['AccountCode', 200]])->first();      
        $accountId = $account->id;
        $share = Shares::where('userId',$userId)->first();

        // If no money

        if(($account->CurrentBalance - $amount)  < 0){
            return response()->json([
                'status' => false,
                'message' => 'sorry, you have insufficient funds to complete the transaction !!!'
            ]);
        }

        $account->CurrentBalance -= $amount;
        if($account->save()){
            // check the user Deposit contribution  and ++ if  not reached

            $userDepositContribution = $share->depositContribution;
            
            $dpContribution = 0;
            $shares = $amount;
            if($userDepositContribution < 5000 ){
                $dpContribution = 5000 - $userDepositContribution;
                $diff = $amount - $dpContribution;
                if($diff > 0){
                    $shares = $diff;
                } else{
                    $dpContribution = $amount;
                    $shares = 0;
                }         
            }

        
            $share->userId = $userId;
            $share->shares += $shares;
            $share->depositContribution += $dpContribution;
            $share->accountId = $accountId;
            if($share->save()){
                return response()->json([
                    'status' => true,
                    'message' => 'CONGRATS, you have successfully deposited '. $amount .' to your shares account'
                ]);
            }else {
                return response()->json([
                    'status' => false,
                    'message' => 'sorry, an error occurred !!!'
                ]);
            }
        }
        
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Shares  $shares
     * @return \Illuminate\Http\Response
     */
    public function show(Shares $shares)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Shares  $shares
     * @return \Illuminate\Http\Response
     */
    public function edit(Shares $shares)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Shares  $shares
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shares $shares)
    {
        //validation
        $this->validate($request,[

            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Shares  $shares
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shares $shares)
    {
        //
    }
}
