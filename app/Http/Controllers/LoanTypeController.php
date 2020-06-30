<?php

namespace App\Http\Controllers;

use App\LoanType;
use Illuminate\Http\Request;

class LoanTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loanTypes = LoanType::all();
        return $loanTypes;
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
            'type' => 'required | unique:loan_types',
            'interest' => 'required | numeric | min:1 | max:90',
            'max_amount' => 'required | numeric',
            'max_period' => 'required | numeric',
        ]);

        $loanType = new LoanType;
        $loanType->type = $request->type;
        $loanType->interest = $request->interest;
        $loanType->maxAmount = $request->max_amount;
        $loanType->maxPeriod = $request->max_period;

        if($loanType->save()){
            return response()->json([
               'status' => true,
               'message' => 'Loan type added successfully !!!'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Oops, An error occured !!!'
         ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LoanType  $loanType
     * @return \Illuminate\Http\Response
     */
    public function show(LoanType $loanType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LoanType  $loanType
     * @return \Illuminate\Http\Response
     */
    public function edit(LoanType $loanType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LoanType  $loanType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id )
    {
        $loanType = LoanType::find($id);
        //validation
        $this->validate($request,[
            'type' => 'required | unique:loan_types,type,'.$loanType->id,
            'interest' => 'required | numeric | min:1 | max:90',
            'max_amount' => 'required | numeric',
            'max_period' => 'required | numeric',
        ]);

        
        $loanType->type = $request->type;
        $loanType->interest = $request->interest;
        $loanType->maxAmount = $request->max_amount;
        $loanType->maxPeriod = $request->max_period;

        if($loanType->save()){
            return response()->json([
               'status' => true,
               'message' => 'Loan type updated successfully !!!'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Oops, An error occured !!!'
         ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LoanType  $loanType
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanType $loanType)
    {
        //
    }
}
