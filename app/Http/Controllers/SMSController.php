<?php

namespace App\Http\Controllers;

use App\SMS;
use Auth;
use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;

class SMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSms()
    {
        $message ="hi";
        

          $no = Auth::user()->phone;
          $new = substr($no,1);
          $phone = '+254'.$new;
          $username = 'cagimu'; 
          $apiKey   = '4364fea1f320e7d417614fc23bd4f8bc312268e29b1cf000c45c0cc0772036e'; //b
            $AT       = new AfricasTalking($username, $apiKey);

            // Get one of the services
            $sms      = $AT->sms();

            // Use the service
            $result   = $sms->send([
                'to'      => $phone,
                'message' => $message
            ]);
           // $data = json_encode($result);
             $data =$result['data'];
             $smsdata =get_object_vars($data);
             $SMSMessageData = $smsdata['SMSMessageData'];
              $msgData = get_object_vars($SMSMessageData);
             $Recipients = $msgData['Recipients'];
             $rcpt = get_object_vars($Recipients[0]);
             $dirtyCost = $rcpt['cost'];
             $cost =substr($dirtyCost,4);
        
             if($result['status']=="success"){
                 $mySms = new SMS;
                 $mySms->userid = Auth::user()->id;
                 $mySms->Message = $message;
                 $mySms->phone = $rcpt['number'];
                 $mySms->amount = $cost;

                 if($mySms->save()){
                    return redirect()->back()->with("success","Course basket Saved Successifully.A confirmmation message has been sent to your phone");
                 }
             }
            return redirect()->back()->with("success","Course basket Saved Successifully.");
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function show(SMS $sMS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function edit(SMS $sMS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SMS $sMS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function destroy(SMS $sMS)
    {
        //
    }
}
