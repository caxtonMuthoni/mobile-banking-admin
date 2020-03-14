<?php

namespace App\Http\Controllers;

use App\Account;
use App\Borrow;
use Illuminate\Http\Request;
use Validator;
use App\User;
use AfricasTalking\SDK\AfricasTalking;
use App\SMS;
use App\Transaction;
use Auth;
use App\Helpers\Transact;
use GuzzleHttp\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accunts = Account::all();
        return $accunts;
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
         
        $this->validate($request,[
	        "Amount"=> "required | numeric",
        ]);

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
        $this->validate($request,[
            "PhoneNumber"=>"required | max:10 | min:10 | regex:/(07)[0-9]{8}/",
	        "Amount"=> "required | numeric",
        ]);
        
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

        $this->validate($request,[
            "MPESATransactionID"=>'required'
        ]);
        return response()->json([
            "status"=>"true",
            'success'=>'Your  deposit was recieved successfully !!!'
        ]);
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
            $Account = Account::where([['CustomerID','=',$user->id],['AccountNumber','=',$user->NationalID]])->first();
            $transaction = new Transaction;
            $transaction->TransactionType = "Deposit";
            $transaction->TransID = $request->MPESATransactionID;
            $transaction->TransAmount = $amount;
            $transaction->UserId = $user->id;
            $transaction->AccountNumber = $Account->AccountNumber;
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
                                'success'=>'Your  deposit was recieved successfully !!!'
                            ]);
                           }
                      }
                      return response()->json([
                        "status"=>"true",
                        'success'=>'Your  deposit was recieved successfully !!!'
                    ]);
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
        $this->validate($request,[
            "PhoneNumber"=>"required | max:10 | min:10 | regex:/(07)[0-9]{8}/",
	        "Amount"=> "required | numeric",
        ]);

        
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
                'error' => "Sorry, You have insufficient funds to complete the transcation."
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
            $Account = Account::where([['CustomerID','=',$user->id],['AccountNumber','=',$user->NationalID]])->first();
            $transaction = new Transaction;
            $transaction->TransactionType = "Withdraw";
            $transaction->TransID = rand(100000,999999);
            $transaction->TransAmount = $amount;
            $transaction->UserId = $user->id;
            $transaction->AccountNumber = $Account->AccountNumber;
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
                                'success'=>'Your  Withdrawal was proccesses successfully !!!'
                            ]);
                           }
                      }
                      return response()->json([
                        "status"=>"true",
                        'success'=>'Your  Withdrawal was proccesses successfully !!!'
                    ]);
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

   
    public function directDeposit(Request $request)
    {
        //Validation
        $this->validate($request,[
            'id'=>"required | numeric",
            'AccountNumber'=>"required | numeric",
            'CurrentBal'=>"required | numeric",
        ]);

        $accountToDeposit = Account::where('AccountNumber',$request->AccountNumber)->first();
        $myBalance = $accountToDeposit->CurrentBalance;
        $user = User::where('id',$accountToDeposit->CustomerID)->first();
        $accountToDeposit->CurrentBalance = $accountToDeposit->CurrentBalance + $request->CurrentBal;
        if($accountToDeposit->save()){
                $transaction = new Transaction;
                $transaction->TransactionType = "Direct deposit by ".auth('api')->user()->FirstName.auth('api')->user()->NationalID;
                $transaction->TransID = rand(100000,999999);
                $transaction->TransAmount = $request->CurrentBal;
                $transaction->UserId = $request->id;
                $transaction->AccountNumber = $request->AccountNumber;
                $transaction->MSISDN = auth('api')->user()->PhoneNumber;
                $transaction->FirstName = $user->FirstName;
                $transaction->MiddleName = $user->MiddleName;
                $transaction->LastName = $user->LastName;
                $transaction->OrgAccountBalance = $myBalance;
                $transaction->CrtAccountBalance = $myBalance + $request->CurrentBal;
                if($transaction->save()){
                    return response()->json([
                        'status'=>'true',
                        "success"=>'deposit proccessed successfully !!!',
                    ]);
                }
        }
    }

    public function directWithdraw(Request $request)
    {
        //Validation
        $this->validate($request,[
            'AccountNumber'=>"required | numeric",
            'CurrentBal'=>"required | numeric",
        ]);

        $accountToDeposit = Account::where('AccountNumber',$request->AccountNumber)->first();
        $myBalance = $accountToDeposit->CurrentBalance;
        $user = User::where('id',$accountToDeposit->CustomerID)->first();
        $accountToDeposit->CurrentBalance = $accountToDeposit->CurrentBalance - $request->CurrentBal;
        if($accountToDeposit->save()){
                $transaction = new Transaction;
                $transaction->TransactionType = "Direct withdraw by ".auth('api')->user()->FirstName.auth('api')->user()->NationalID;
                $transaction->TransID = rand(100000,999999);
                $transaction->TransAmount = $request->CurrentBal;
                $transaction->UserId = $request->id;
                $transaction->AccountNumber = $request->AccountNumber;
                $transaction->MSISDN = auth('api')->user()->PhoneNumber;
                $transaction->FirstName = $user->FirstName;
                $transaction->MiddleName = $user->MiddleName;
                $transaction->LastName = $user->LastName;
                $transaction->OrgAccountBalance = $myBalance;
                $transaction->CrtAccountBalance = $myBalance - $request->CurrentBal;
                if($transaction->save()){
                    return response()->json([
                        'status'=>'true',
                        "success"=>'withdrawal proccessed successfully !!!',
                    ]);
                }
        }
    }


   
    public function openAccount(Request $request)
    {
        /* Validation */
        $validator = Validator::make($request->all(),[
           "AccountCode"=>"required",
           "AccountName" => "required |regex:/^[\pL\s\-]+$/u | unique:accounts",
        ]);

        if($validator->fails()){
            return $validator->errors();
        }

        $accounts  = Account::where('CustomerID',Auth::user()->id)->count();
        if ($accounts >=5) {
            return response()->json([
                'status' => 'false',
                'error'=>"You have reached the maximum number of accounts required !!!"
            ]);
        }
        $accountNo = Auth::user()->NationalID;
        $accountCode = $request->AccountCode;
        $accountNumber = $accountCode.$accountNo;

        $account = new Account;
        $account->AccountName = $request->AccountName;
        $account->AccountNumber = $accountNumber;
        $account->CustomerID = Auth::user()->id;
        $account->CurrentBalance = 0.0;
        $account->Status = true;
        if($account->save()){
            return response()->json([
                'status' => 'true',
                'success' => "Account created successifully !!!"
            ]);
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

    public function usersCount(){
        $username = 'cagimu'; 
        $apiKey   = '4364fea1f320e7d417614fc23bd4f8bc312268e29b1cf000c45c0cc0772036eb'; 
        $AT       = new AfricasTalking($username, $apiKey);
        //Checking Account Balance
        $data = $AT->application()->fetchApplicationData();
        $newData = $data['data'];
        $UserData = get_object_vars($newData);
        $newUserData = $UserData['UserData'];
        $balanced = get_object_vars($newUserData);
        $newBalance = $balanced['balance'];
        $BAL = substr($newBalance,4);

     $smses = SMS::all();
     $cost = 0;
     foreach($smses as $sms){
       $cost = $cost + $sms->amount;
     }
        $users = User::get()->count();
        $loans = Borrow::all()->count();
        return response()->json([
            'users'=>$users,
            'loans'=>$loans,
            'usage'=>round($cost,3),
            'bal'=>round($BAL,2),
        ]);
    }


    public function getUsersAccounts(){
        $userId = auth('api')->user()->id;
        $accounts = Account::where('CustomerID',$userId)->get();
        return $accounts;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */

     public function buyAirtime( Request $request){

        /* validation  */
        $this->validate($request,[
            'phone'=>'required',
            'amount'=>'required | numeric | min:10 | max:500'
        ]);
        /* Get user */
        $user = auth('api')->user();
        /* Get Account */
        $account = Account::where('CustomerID',$user->id)->first();
        if($account->CurrentBalance < $request->amount){
            return response()->json([
                'status'=>'false',
                'error'=>'You have insufficient funds to execute the transaction'
            ]);
        }
        //API URL
        $url = 'https://renthero.co.ke/phpsap/developer/payments/airtime.php';
        $username ='caxton';
        $apiKey = '8ZITSV4TN4aRSjH5eYTCKAP6nUaxIfxL8V0xMuueRFNunW7DL5bq1cf6D3878U92';
        $Amount = $request->amount;
        $phone = substr($request->phone,1);
        $PhoneNumber = '+254'.$phone;
    
        //create a new cURL resource
        $ch = curl_init($url);

        //setup request to send json via POST
        $data = array(
            'username' => $username,
            'apiKey' => $apiKey,
            "Receiver"=>$PhoneNumber,
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
            $account->CurrentBalance = $account->CurrentBalance - $request->amount;
            if($account->save()){
                $transact = new Transact;
                $status = $transact->transact(
                    "airtime Purchase",
                    rand(1000000,96859699365),
                     $request->amount,
                    $user->id,
                    $account->AccountNumber,
                    $user->PhoneNumber,
                    $user->FirstName,
                    $user->MiddleName,
                    $user->LastName,
                    $account->CurrentBalance+$request->amount,
                    $account->CurrentBalance
                  );
         
                  if($status){
                      return response()->json([
                       "status"=>"true",
                        "success"=>" You have successifully purchased ". $request->amount. " airtime."
                      ]);
                  }
         
            }
    
        }else{
            $datas = json_decode($result,true);
          return response()->json([
              "status"=>'false',
              "error"=>$datas['response']
          ]);
        }
       
     }

     public function payBill(Request $request){
         /* Validation */
         $this->validate($request,[
             'DestinationChannel'=> 'required | string | min:5 | max:7',
             'DestinationAccount' => 'required',
             'amount'=>'required | numeric | min:10| max: 70000'
         ]);

         /* Get user */
        $user = auth('api')->user();
        /* Get Account */
        $account = Account::where('CustomerID',$user->id);

         $apiKey="8ZITSV4TN4aRSjH5eYTCKAP6nUaxIfxL8V0xMuueRFNunW7DL5bq1cf6D3878U92";
         $DestinationChannel=$request->DestinationChannel;
         $DestinationAccount =$request->DestinationAccount;
         $Amount = $request->amount;
         $url = "https://renthero.co.ke/phpsap/developer/payments/sapb2b.php";

          //create a new cURL resource
        $ch = curl_init($url);

        //setup request to send json via POST
        $data = array(
            'username' => 'caxton',
            'apiKey' => $apiKey,
            "DestinationChannel"=>$DestinationChannel,
            "DestinationAccount" => $DestinationAccount,
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
            $account->CurrentBalance = $account->CurrentBalance - $request->amount;
            if($account->save()){
                $transact = new Transact;
                $status = $transact->transact(
                    "PayBill ".$DestinationChannel." Account ".$DestinationAccount,
                    rand(1000000,96859699365),
                    $request->amount,
                    $user->id,
                    $account->AccountNumber,
                    $user->PhoneNumber,
                    $user->FirstName,
                    $user->MiddleName,
                    $user->LastName,
                    $account->CurrentBalance+$request->amount,
                    $account->CurrentBalance
                  );
         
                  if($status){
                      return response()->json([
                       "status"=>"true",
                        "success"=>" You have successifully paid ". $request->amount. " to PayBill ".$DestinationChannel." to account ".$DestinationAccount,
                      ]);
                  }
         
            }
    
        }else{
            $datas = json_decode($result,true);
          return response()->json([
              "status"=>'false',
              "error"=>$datas['response']
          ]);
        }
       
     }

     public function accountStatement(Request $request){
         $this->validate($request,[
             'accountNumber'=>'required | numeric '
         ]);
         $statement = Transaction::where('AccountNumber',$request->accountNumber)->latest()->get();
         return $statement;
     }
     
    public function destroy($id)
    {
        $account =  Account::where([['id','=',$id],['CustomerID','=',Auth::user()->id]])->first();
        if($account->CurrentBalance > 0){
            return response()->json([
                'status'=>'false',
                'error'=>"The current balance must be Zero please tranfer funds to another account and try again."
            ]);
        }
        else{
            if($account->delete()){
                return response()->json([
                    'status'=>'true',
                    'success'=>"The account was deleted successifully !!!."
                ]);
            }
        }
    }
}
