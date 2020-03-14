<?php

namespace App\Http\Controllers;

use App\AccountType;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accountTypes = AccountType::all();
        return $accountTypes;
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
        //validaton
        $this->validate($request,[
            'Name'=>'required | unique:account_types',
            'Code'=>'required | numeric | unique:account_types',
            'Fee'=>'required | numeric'
        ]);

        $accountType = new AccountType;
        $accountType->Name = $request->Name;
        $accountType->Code = $request->Code;
        $accountType->Fee = $request->Fee;
        if($accountType->save()){
            return response()->json([
                'status'=>"true",
                "success"=>"Accoutn type was added successifully !!!"
            ]);
        }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function show(AccountType $accountType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountType $accountType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $accountType = AccountType::find($id);
         //validaton
         $this->validate($request,[
            'Name'=>'required | max:20 | unique:account_types,Name,'.$accountType->id,
            'Fee'=>'required | numeric | max:3',
            'Code'=>'required | max:3 | unique:account_types,Code,'.$accountType->id,
        ]);

       
        $accountType->Name = $request->Name;
        $accountType->Code = $request->Code;
        $accountType->Fee = $request->Fee;
        if($accountType->save()){
            return response()->json([
                'status'=>"true",
                "success"=>"Account type was updated successifully !!!"
            ]);
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AccountType  $accountType
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountType $accountType)
    {
        //
    }
}
