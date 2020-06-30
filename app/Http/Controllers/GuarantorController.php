<?php

namespace App\Http\Controllers;

use App\Guarantor;
use Illuminate\Http\Request;
use App\Loan;
use App\Shares;
use App\Http\Resources\GuarantorCollecion;

class GuarantorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Guarantor::all();
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
           'loanId' => 'required | numeric',
           'amount' => 'required | numeric | min:1000'
        ]);

        $user = auth('api')->user();
        $userId = $user->id;
        $loanId = $request->loanId;
        $amount = $request->amount; 
        $loan = Loan::find($loanId);
        $userActiveGuaranted = Guarantor::where([['guarantor','=',$userId],['status','=',true]])->get();

        $shares =  Shares::find($userId);
        if($shares == null || $shares->shares < $amount){
            return response()->json([
                'status' => false,
                'message' => 'Sorry you have insufficient shares !!!'
            ]);
        }
        elseif($userActiveGuaranted->count() >= 5){
            return response()->json([
                'status' => false,
                'message' => 'Sorry, you reached the maximum number of guranteed Loans: 5-loans !!!'
            ]);
        }

        elseif ($userActiveGuaranted->count() > 0) {
             $totalGuarantedAmt = 0;
             foreach ($userActiveGuaranted as $guarantee ){
                  $totalGuarantedAmt += $guarantee->amount;
                        
             }
             if ($totalGuarantedAmt >= $shares->shares) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sorry, you have fully guaranteed all of your shares !!!'
                ]);
             }
             else {
                 $amount = $shares->shares - $totalGuarantedAmt;
             }
        }

        elseif($loan->guaranteeStatus){
            return response()->json([
                'status' => false,
                'message' => 'Sorry, the loan is fully guaranteed, Thanks for your support !!!'
            ]);
        }

        elseif ($loan->userId == $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, you can\'t gurantee your own loan !!!'
            ]);
        }

        if ($loan->guaranteedAmount > 0) {
             $guarantorBal = $loan->guarantorAmount - $loan->guaranteedAmount;
             if ($guarantorBal < $amount) {
                 $amount = $guarantorBal;
             }

             
        }
        
        
        

            $guarantor = new Guarantor;
            $guarantor->guarantor = $userId;
            $guarantor->amount = $amount;
            $guarantor->loanId = $loanId;
            if($guarantor->save()){
                 $loan = Loan::find($loanId);
                 $loan->guaranteedAmount = $loan->guaranteedAmount + $amount;
                 if($loan->guaranteedAmount >= $loan->guarantorAmount ){
                     $loan->guaranteeStatus = true;
                 }
                 $loan->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Confirmed you have guanteed KSH: '.$amount.' successfully !!!'
                ]);
            }
        
    }

    public function getLoanGuarantors($id){
        $loanId = Loan::where('id',$id)->value('id');
        $guarantors = Guarantor::where('loanId',$loanId)->latest()->get();
        return GuarantorCollecion::collection($guarantors);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Guarantor  $guarantor
     * @return \Illuminate\Http\Response
     */
    public function show(Guarantor $guarantor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guarantor  $guarantor
     * @return \Illuminate\Http\Response
     */
    public function edit(Guarantor $guarantor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Guarantor  $guarantor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guarantor $guarantor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Guarantor  $guarantor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guarantor $guarantor)
    {
        //
    }
}
