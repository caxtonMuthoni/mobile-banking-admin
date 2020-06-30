<?php

namespace App\Http\Controllers;

use App\GroupAccount;
use Illuminate\Http\Request;
use App\Account;

class GroupAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GroupAccount::all();
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
            'accountId' => 'required | numeric | min:0',
        ]);

        // Check the account
        $account = Account::find($request->accountId);
        if ($account == null) {
            return response()->json([
                'status' => false,
                'message' => 'The account has been closed !!!'
            ]);
        }

        $role = 'member';
        $groupAccount = GroupAccount::where('accountId', $request->accountId)->first();
        if ($groupAccount == null){
            $role = 'admin';
        }
        $userInGroup = GroupAccount::where([['userId',auth('api')->user()->id],['accountId',$request->accountId]])->get()->count();
        if($userInGroup > 0){
            return response()->json([
                'status' => false,
                'message' => 'Your are a member of this group !!!'
            ]);
        }
        $groupAccount = new GroupAccount;
        $groupAccount->accountId = $request->accountId;
        $groupAccount->userId = auth('api')->user()->id;
        $groupAccount->role = $role;
        if($groupAccount->save()){
            return response()->json([
                'status' => true,
                'message' => 'The Group Account was created successfully !!!'
            ]);
        } 
        return response()->json([
            'status' => false,
            'message' => 'Oops an error occurred !!!'
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\GroupAccount  $groupAccount
     * @return \Illuminate\Http\Response
     */
    public function show(GroupAccount $groupAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GroupAccount  $groupAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(GroupAccount $groupAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GroupAccount  $groupAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GroupAccount $groupAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GroupAccount  $groupAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupAccount $groupAccount)
    {
        //
    }
}
