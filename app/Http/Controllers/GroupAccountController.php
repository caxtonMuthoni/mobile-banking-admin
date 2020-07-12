<?php

namespace App\Http\Controllers;

use App\GroupAccount;
use Illuminate\Http\Request;
use App\Account;
use App\Profile;
use App\User;
use  App\Http\Resources\GroupAccountResource;

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
/* ============================= Join Group Request ============================= */
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


    /* ============================= User group accounts ============================= */
    public function getuserGroupAccounts(){
        $userId = auth('api')->user()->id;
        $groupAccounts = GroupAccount::where('userId',$userId)->get();
        return GroupAccountResource::collection($groupAccounts);
    }

     /* ============================= Get Account By Acccount number ============================= */
     public function getAcountByAccountNumber(Request $request){
        //  validation
        $this->validate($request,[
            'AccountNumber' => 'required'
        ]);
        $account = Account::where('AccountNumber',$request->AccountNumber)->first();

        if($account != null){
            return response()->json([
                'status' => true,
                'account' => $account,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => "The account does not exixts",
        ]);
     }

     /* ============================= Get Group Members ============================= */
     public function getGroupMembers(Request $request){
        //  validation
        $this->validate($request,[
            'accountId' => 'required | numeric',
        ]);
        $members = GroupAccount::where('accountId',$request->accountId)->get();
        $groupMembers = [];
        foreach($members as $member){
           $user = User::find($member->userId);
           $profile = Profile::where('UserId',$user->id)->first();
           $json = [
               'id' =>  $member->id,
               'userId'=> $member->userId,
               'name' => $user->FirstName.' '.$user->LastName,
               'nationalId' => $user->NationalID,
               'role' => $member->role,
               'joined_at' => $member->created_at,
               'status' => $member->status,
               'avatar' => $profile->Avatar,
           ];
           array_push($groupMembers,$json);
        }



        return $groupMembers;
     }
    
}
