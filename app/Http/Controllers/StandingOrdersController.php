<?php

namespace App\Http\Controllers;

use App\StandingOrders;
use Illuminate\Http\Request;
use DateTime;
use App\Http\Controllers\TransactionController;

class StandingOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return StandingOrders::all();
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
    
         $transaction =  new TransactionController;
         $transaction->transact($request);
         $transaction->transact($request2);
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
        'accountId' => 'required | numeric | min:1',
        'destinationId' => 'required | numeric | min:1',
        'amount' => 'required | numeric | min:100',
        'duration' => 'required | numeric | min:1, max:12',
    ]);

    $today = new DateTime('now');
    $today->modify('+'.$request->duration.'month');
    $nextDate =$today->format('Y-m-d h:i:s');

    

    $standingOrder = new StandingOrders;
    $standingOrder->userId = auth('api')->user()->id;
    $standingOrder->accountId = $request->accountId;
    $standingOrder->destinationId = $request->destinationId  ;
    $standingOrder->amount = $request->amount ;
    $standingOrder->duration = $request->duration ;
    $standingOrder->nextOrder = $nextDate;
        if($standingOrder->save()){
            return response()->json([
                'status' => true,
                'message' => 'The  standing order was added successfully  !!!'
            ]);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\StandingOrders  $standingOrders
     * @return \Illuminate\Http\Response
     */
    public function show(StandingOrders $standingOrders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StandingOrders  $standingOrders
     * @return \Illuminate\Http\Response
     */
    public function edit(StandingOrders $standingOrders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StandingOrders  $standingOrders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'accountId' => 'required | numeric | min:1',
            'destinationId' => 'required | numeric | min:1',
            'amount' => 'required | numeric | min:100',
            'duration' => 'required | numeric | min:1, max:12',
        ]);
    
        $today = new DateTime('now');
        $today->modify('+'.$request->duration.'month');
        $nextDate =$today->format('Y-m-d h:i:s');
         $date = $today->format('Y-m-d h:i:s');
        $standingOrder = StandingOrders::find($id);
         
     
       
        $standingOrder->userId = auth('api')->user()->id;
        $standingOrder->accountId = $request->accountId;
        $standingOrder->destinationId = $request->destinationId  ;
        $standingOrder->amount = $request->amount ;
        $standingOrder->duration = $request->duration ;
        $standingOrder->nextOrder = $nextDate;
            if($standingOrder->save()){
                return response()->json([
                    'status' => true,
                    'message' => 'The  standing order was updated successfully  !!!'
                ]);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StandingOrders  $standingOrders
     * @return \Illuminate\Http\Response
     */
    public function destroy(StandingOrders $standingOrders)
    {
        //
    }
}
