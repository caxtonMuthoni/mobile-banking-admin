<?php

namespace App\Http\Controllers;

use App\transaction_cost;
use Illuminate\Http\Request;

class TransactionCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return transaction_cost::all();
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
            'type' => 'required | unique:transaction_costs',
            'cost' => 'required | numeric | min:0 | max : 200'
        ]);

        $transaction_cost = new transaction_cost;
        $transaction_cost->type = $request->type;
        $transaction_cost->cost = $request->cost;

        if($transaction_cost->save()){
            return response()->json([
                'status' => true,
                'message' => 'You have successfully created new transaction cost.'
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Oops an error occurred'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\transaction_cost  $transaction_cost
     * @return \Illuminate\Http\Response
     */
    public function show(transaction_cost $transaction_cost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\transaction_cost  $transaction_cost
     * @return \Illuminate\Http\Response
     */
    public function edit(transaction_cost $transaction_cost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\transaction_cost  $transaction_cost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $transaction_cost = transaction_cost::find($id);
         //validation
         $this->validate($request,[
            'type' => 'required | unique:transaction_costs,type,'.$id,
            'cost' => 'required | numeric | min:0 | max : 200'
        ]);

        $transaction_cost->type = $request->type;
        $transaction_cost->cost = $request->cost;

        if($transaction_cost->save()){
            return response()->json([
                'status' => true,
                'message' => 'You have successfully updated transaction cost.'
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'message' => 'Oops an error occurred'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\transaction_cost  $transaction_cost
     * @return \Illuminate\Http\Response
     */
    public function destroy(transaction_cost $transaction_cost)
    {
        //
    }
}
