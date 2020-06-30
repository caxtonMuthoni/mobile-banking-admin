<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\User;
use App\Account;
use App\Http\Resources\TransactionCollection;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = TransactionCollection::collection(Transaction::latest()->paginate(8));
        return $transactions;
    }

    public function userTransactions(Request $request){
        $this->validate($request,[
            "MSISDN"=>"required | max:10 | min:10 | regex:/(07)[0-9]{8}/",
        ]);
        $transactions = Transaction::where('MSISDN',$request->MSISDN)->get();
        $user = User::where('PhoneNumber',$request->MSISDN)->first();
        $total = Account::where('CustomerID',$user->id)->value('CurrentBalance');
        return response()->json([
            'user' => $user,
            'transactions'=>TransactionCollection::collection($transactions),
            'total' => $total
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request = [
            'TransactionType' => 0,
            'TransactionDescription' => 0,
            'TransID' => 0,
            'UserId' => 0,
            'AccountNumber' => 0,
            'MSISDN' => 0,
            'FirstName' => 0,
            'MiddleName' => 0,
            'LastName' => 0,
            'TransAmount' => 0,
            'OrgAccountBalance' => 0,
            'CrtAccountBalance' => 0,
        ];
         TransactionController::transact($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function transact($request)
    {
        // try {
            $transaction = new Transaction;
            $transaction->TransactionType = $request['TransactionType'];
            $transaction->TransactionDescription = $request['TransactionDescription'];
            $transaction->TransID = $request['TransID'];
            $transaction->UserId = $request['UserId'];
            $transaction->AccountNumber = $request['AccountNumber'];
            $transaction->MSISDN = $request['MSISDN'];
            $transaction->FirstName = $request['FirstName'];
            $transaction->MiddleName = $request['MiddleName'];
            $transaction->LastName = $request['LastName'];
            $transaction->TransAmount = $request['TransAmount'];
            $transaction->OrgAccountBalance = $request['OrgAccountBalance'];
            $transaction->CrtAccountBalance = $request['CrtAccountBalance'];

            $transaction->save();
            return $transaction;
            
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
    }

            

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
