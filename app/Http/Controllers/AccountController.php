<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Validator;
use App\User;
use AfricasTalking\SDK\AfricasTalking;
use App\SMS;
use App\Transaction;

class AccountController extends Controller
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
    public function create()
    {
        //
    }

    public function topUpSap(Request $request){
         
        $valitator = Validator::make($request->all(),[
	        "Amount"=> "required | numeric",
        ]);
        if($valitator->fails()){
            return $valitator->errors();
        }

        $username = "caxton";
        $apiKey = "8ZITSV4TN4aRSjH5eYTCKAP6nUaxIfxL8V0xMuueRFNunW7DL5bq1cf6D3878U92";
	    $Amount=$request->Amount;
        
        //API URL
        $url = 'https://renthero.co.ke/phpsap/developer/payments/topupsapwallet.php';

        //create a new cURL resource
        $ch = curl_init($url);

        //setup request to send json via POST
        $data = array(
            'username' => $username,
            'apiKey' => $apiKey,
	        "Amount"=> $Amount,
        );
        $payload = json_encode($data);
        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Accept:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);
        $data = json_decode($result,true);
        return $data;
        //close cURL resource
        curl_close($ch);

    }
    
    public function STKPush(Request $request){
        $valitator = Validator::make($request->all(),[
            "PhoneNumber"=>"required | max:10 | min:10 | regex:/(07)[0-9]{8}/",
	        "Amount"=> "required | numeric",
        ]);
        if($valitator->fails()){
            return $valitator->errors();
        }
        $phone = $request->PhoneNumber;
        $PhoneNumber = "+254".substr($phone,1);
        $username = "caxton";
        $apiKey = "8ZITSV4TN4aRSjH5eYTCKAP6nUaxIfxL8V0xMuueRFNunW7DL5bq1cf6D3878U92";
	    $Amount=$request->Amount;
        
        //API URL
        $url = 'https://renthero.co.ke/phpsap/developer/payments/lnmo.php';

        //create a new cURL resource
        $ch = curl_init($url);

        //setup request to send json via POST
        $data = array(
            'username' => $username,
            'apiKey' => $apiKey,
            "PhoneNumber"=>$PhoneNumber,
	        "Amount"=> $Amount,
        );
        $payload = json_encode($data);
        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Accept:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);
        return $result;
        //close cURL resource
        curl_close($ch);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deposit(Request $request)
    {   

        $valitator = Validator::make($request->all(),[
            "MPESATransactionID"=>'required'
        ]);
        if($valitator->fails()){
            return $valitator->errors();
        }
        $username = "caxton";
        $apiKey = "8ZITSV4TN4aRSjH5eYTCKAP6nUaxIfxL8V0xMuueRFNunW7DL5bq1cf6D3878U92";
        $MPESATransactionID = $request->MPESATransactionID;
        //API URL
        $url = 'https://www.renthero.co.ke/phpsap/developer/payments/sapc2b_validation.php';

        //create a new cURL resource
        $ch = curl_init($url);

        //setup request to send json via POST
        $data = array(
            'username' => $username,
            'apiKey' => $apiKey,
            'MPESATransactionID' => $MPESATransactionID ,
        );
        $payload = json_encode($data);
        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);
         $datas = json_decode($result,true);

         if($datas["status"]){
            $phone = $datas['response']['source'];
            $amount = $datas['response']['Amount'];
            $newPhone = "0".substr($phone,4);
            $user = User::where('PhoneNumber',$newPhone)->first();
            $Account = Account::where('CustomerID',$user->id)->first();
            $transaction = new Transaction;
            $transaction->TransactionType = "Deposit";
            $transaction->TransID = $phone;
            $transaction->TransAmount = $amount;
            $transaction->Account = $user->id;
            $transaction->MSISDN = $newPhone;
            $transaction->FirstName = $user->FirstName;
            $transaction->MiddleName = $user->MiddleName;
            $transaction->LastName = $user->LastName;
            $transaction->OrgAccountBalance = $Account->CurrentBalance;
            $transaction->CrtAccountBalance = $Account->CurrentBalance + $amount;
            
            if($transaction->save()){
                $Account->CurrentBalance = $Account->CurrentBalance + $amount;
                if($Account->save()){
                    $no = $phone;
                    $username = 'cagimu'; 
                    $apiKey   = '4364fea1f320e7d417614fc23bd4f8bc312268e29b1cf000c45c0cc0772036eb'; 
                    $AT       = new AfricasTalking($username, $apiKey);
                    $message = "Dear, ".$user->FirstName." ".$user->LastName.", your deposit of KES ". $amount ." from ".$phone." has been recievied. Thanks for banking with us.";
            
                      // Get one of the services
                      $sms      = $AT->sms();
            
                      // Use the service
                      try {
                          $result   = $sms->send([
                              'to'      => $phone,
                              'message' => $message
                          ]);
                      } catch (\Throwable $th) {
                          return response()->json(
                              [
                                "status"=>"false",
                                'error'=>"Oops Please make sure you are connected to internet."
                              ]
                          );
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
                           $mySms->userid = $user->id;
                           $mySms->Message = $message;
                           $mySms->phone = $rcpt['number'];
                           $mySms->amount = $cost;
            
                           if($mySms->save()){
                             
                      
                            return response()->json([
                                "status"=>"true",
                                'success'=>'Your  deposit was recieved successifully !!!'
                            ]);
                           }
                      }
                }
            }
            
            
         }
         else{
            $error = $datas['response']['Description'];
            return response()->json([
                "status"=>"false",
                'error'=>$error
            ]);
         }
        //close cURL resource
        curl_close($ch);
    }

    /* Withdraw */
    public function withdraw(Request $request){
        $valitator = Validator::make($request->all(),[
            "PhoneNumber"=>"required | max:10 | min:10 | regex:/(07)[0-9]{8}/",
	        "Amount"=> "required | numeric",
        ]);
        if($valitator->fails()){
            return $valitator->errors();
        }
        $phone = $request->PhoneNumber;
        $Amount=$request->Amount;
        $user = User::where('PhoneNumber',$phone)->first();
        if($user === null){
            return response()->json([
                "status"=>"false",
                'error' => 'Sorry, the phonenumber you entered does not exist in our systems.Please create an account in order to deposit !!!'
            ]);
        }
        $accountBal = Account::where('CustomerID',$user->id)->value('CurrentBalance');
        if($accountBal < $Amount){
            return response()->json([
                "status"=>"false",
                'error' => "You don,t Have enough Money to perfom the trnscation."
            ]);
        }
        else{
        $PhoneNumber = "+254".substr($phone,1);
        $username = "caxton";
        $apiKey = "8ZITSV4TN4aRSjH5eYTCKAP6nUaxIfxL8V0xMuueRFNunW7DL5bq1cf6D3878U92";

        //API URL
        $url = 'https://renthero.co.ke/phpsap/developer/payments/sapb2c.php';

        //create a new cURL resource
        $ch = curl_init($url);

        //setup request to send json via POST
        $data = array(
            'username' => $username,
            'apiKey' => $apiKey,
            "PhoneNumber"=>$PhoneNumber,
	        "Amount"=> $Amount,
        );
        $payload = json_encode($data);
        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);
        $datas = json_decode($result,true);
        if($datas["status"]){
            $phone = $datas['response']['Destination'];
            $amount = $datas['response']['Amount'];
            $newPhone = "0".substr($phone,4);
            $user = User::where('PhoneNumber',$newPhone)->first();
            $Account = Account::where('CustomerID',$user->id)->first();
            $transaction = new Transaction;
            $transaction->TransactionType = "Withdraw";
            $transaction->TransID = $request->MPESATransactionID;
            $transaction->TransAmount = $amount;
            $transaction->Account = $user->id;
            $transaction->MSISDN = $newPhone;
            $transaction->FirstName = $user->FirstName;
            $transaction->MiddleName = $user->MiddleName;
            $transaction->LastName = $user->LastName;
            $transaction->OrgAccountBalance = $Account->CurrentBalance;
            $transaction->CrtAccountBalance = $Account->CurrentBalance - $amount;
            
            if($transaction->save()){
                $Account->CurrentBalance = $Account->CurrentBalance - $amount;
                if($Account->save()){
                    $no = $phone;
                    $username = 'cagimu'; 
                    $apiKey   = '4364fea1f320e7d417614fc23bd4f8bc312268e29b1cf000c45c0cc0772036eb'; 
                    $AT       = new AfricasTalking($username, $apiKey);
                    $message = "Dear, ".$user->FirstName." ".$user->LastName.", comfirmed you have withdrawn KES ". $amount ." to ".$phone." has been recievied. Thanks for banking with us.";
            
                      // Get one of the services
                      $sms      = $AT->sms();
            
                      // Use the service
                      try {
                          $result   = $sms->send([
                              'to'      => $phone,
                              'message' => $message
                          ]);
                      } catch (\Throwable $th) {
                          return response()->json(
                              [
                                "status"=>"false",
                                'error'=>"Oops Please make sure you are connected to internet."
                              ]
                          );
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
                           $mySms->userid = $user->id;
                           $mySms->Message = $message;
                           $mySms->phone = $rcpt['number'];
                           $mySms->amount = $cost;
            
                           if($mySms->save()){
                             
                      
                            return response()->json([
                                "status"=>"true",
                                'success'=>'Your  deposit was recieved successifully !!!'
                            ]);
                           }
                      }
                }
            }
            
            
         }
        else{
            $error = $datas['response'];
            return response()->json([
                "status"=>"false",
                'error'=>$error
            ]);
        }
     }
    }

    public function checkBalance()
    {
        $username = "caxton";
        $apiKey = "8ZITSV4TN4aRSjH5eYTCKAP6nUaxIfxL8V0xMuueRFNunW7DL5bq1cf6D3878U92";
        
        //API URL
        $url = 'https://renthero.co.ke/phpsap/developer/payments/sap_wallet_balance.php';

        //create a new cURL resource
        $ch = curl_init($url);

        //setup request to send json via POST
        $data = array(
            'username' => $username,
            'apiKey' => $apiKey
        );
        $payload = json_encode($data);
        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);
        return $result;
        //close cURL resource
        curl_close($ch);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

   
    public function openAccount(Request $request)
    {
        /* Validation */
        $validator = Validator::make($request->all(),[
           "AccountType"=>"required",
           "AccountName" => "required",
        ]);

        if($validator->fails()){
            return $validator->errors();
        }
        $accountNo = Auth::user()->NationalID;
        $accountType = $request->AccountType;
        switch($accountType){
            case 'normal' :
                $accountNumber = "100".$accountNo;
            break;
            case 'organisation':
                $accountNumber ="200".$accountNo;

        }

       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        //
    }
}
