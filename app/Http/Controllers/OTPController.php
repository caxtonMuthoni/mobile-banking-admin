<?php

namespace App\Http\Controllers;

use App\OTP;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\SMS;
use AfricasTalking\SDK\AfricasTalking;

class OTPController extends Controller
{
/* Generating OTP and sending it to the user */
  public static function generateOtp(Request $request){
    $otp = rand(1000,9999);
    $phone = Auth::user()->PhoneNumber;
    $newOtp = new OTP;
    $newOtp->userid = Auth::user()->id;
    $newOtp->PhoneNumber = $phone;
    $newOtp->OTP= $otp;
    if($newOtp->save()){

        $no = $phone;
        $new = substr($no,1);
        $phone = '+254'.$new;
        $username = 'cagimu'; 
        $apiKey   = '4364fea1f320e7d417614fc23bd4f8bc312268e29b1cf000c45c0cc0772036eb'; 
        $AT       = new AfricasTalking($username, $apiKey);
        $message = "Your security code is: " .$otp;

            // Get one of the services
            $sms      = $AT->sms();

            // Use the service
            try {
                $result   = $sms->send([
                    'to'      => $phone,
                    'message' => $message
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    "status"=>"false",
                    'error'=>"Oops Please make sure you are connected to internet."
                    ]);
            }
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
                    return response()->json([
                        "status"=>"true",
                        "success" => "OTP sent successifully."
                    ]);
                }
            }
        }
  }

  public static function checkOTP(Request $request){
      /* Validation */
      $validator =  Validator($request->all(),[
         'otp' => 'required | max:4',
      ]);
      if($validator->fails()){
          return $validator->errors();
      }
      $oTP = OTP::where([['OTP','=',$request->otp],['Status','=',false],['PhoneNumber','=',Auth::user()->PhoneNumber]])->first();
       if($oTP === null){
           return response()->json([
               "status" => "false",
               "error" => "Invalid security code"
           ]);
       }
       else{
           $oTP->Status=true;
           if($oTP->save()){
            return response()->json([
                "status" => "true",
                "Success" => "Access granted"
            ]);
           }
       }
    }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OTP  $oTP
     * @return \Illuminate\Http\Response
     */
    public function show(OTP $oTP)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OTP  $oTP
     * @return \Illuminate\Http\Response
     */
    public function edit(OTP $oTP)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OTP  $oTP
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OTP $oTP)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OTP  $oTP
     * @return \Illuminate\Http\Response
     */
    public function destroy(OTP $oTP)
    {
        //
    }
}
