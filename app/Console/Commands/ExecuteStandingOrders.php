<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TransactionController;
use App\StandingOrders;
use App\Shares;
use App\Account;
use App\User;
use DateTime;

class ExecuteStandingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute:standingOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executing standing orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $standingOrders = StandingOrders::all();
        foreach( $standingOrders as $standingOrder){
              $now = new DateTime('now');
              $nextOrder = new DateTime($standingOrder->nextOrder);
              $dateDiff = $nextOrder->diff($now)->days;
              echo("Date diff is: ".$dateDiff);
              if($dateDiff < 1){
                  $source = $standingOrder->accountId;
                  $destination = $standingOrder->destinationId;
                  $now->modify('+'.$standingOrder->duration.' month');
                  $nextDate = $now->format('Y-m-d h:i:s');
                 
                  $sourceAccount = Account::where('id',$source)->first();
                  $originalBal = $sourceAccount->CurrentBalance;
                  $user = User::find($sourceAccount->CustomerID);
                  $destinationAccount = Shares::where('id',$destination)->first();
                  $originalShareBal = $destinationAccount->shares;
                  if (($sourceAccount->CurrentBalance >= $standingOrder->amount) && $standingOrder->status) {
                    $sourceAccount->CurrentBalance -= $standingOrder->amount;
                    if( $sourceAccount->save()){
                          $userDepositContribution = $destinationAccount->depositContribution;
                          $request = [
                            'TransactionType' => 'transfer',
                            'TransactionDescription' => "Shares Standing order",
                            'TransID' => random_int(100000,10000000000),
                            'UserId' => $sourceAccount->CustomerID,
                            'AccountNumber' => $sourceAccount->AccountNumber,
                            'MSISDN' => $user->PhoneNumber,
                            'FirstName' => $user->FirstName,
                            'MiddleName' => $user->MiddleName,
                            'LastName' => $user->LastName,
                            'TransAmount' => $standingOrder->amount,
                            'OrgAccountBalance' => $originalBal,
                            'CrtAccountBalance' => $sourceAccount->CurrentBalance,
                        ];
                        $transaction =  new TransactionController;
                        $transaction->transact($request);
                           $dpContribution = 0;
                           $shares = $standingOrder->amount;
                           if($userDepositContribution < 5000 ){
                               $dpContribution = 5000 - $userDepositContribution;
                               $diff = $amount - $dpContribution;
                               if($diff > 0){
                                   $shares = $diff;
                               } else{
                                   $dpContribution = $standingOrder->amount;
                                   $shares = 0;
                               }         
                           }  
                           
                           $destinationAccount->shares += $shares;
                           $destinationAccount->depositContribution += $dpContribution;
                          if ($destinationAccount->save()){
                            $request = [
                                'TransactionType' => 'transfer',
                                'TransactionDescription' => "Shares deposit",
                                'TransID' => random_int(100000,10000000000),
                                'UserId' => $destinationAccount->userId,
                                'AccountNumber' => $sourceAccount->AccountNumber,
                                'MSISDN' => $user->PhoneNumber,
                                'FirstName' => $user->FirstName,
                                'MiddleName' => $user->MiddleName,
                                'LastName' => $user->LastName,
                                'TransAmount' => $standingOrder->amount,
                                'OrgAccountBalance' => $originalShareBal,
                                'CrtAccountBalance' => $destinationAccount->shares,
                            ];
                            $transaction->transact($request);
                          }
                    }
                  }
                  

                 $standingOrder->nextOrder = $nextDate;
                 $standingOrder->save();

              }
        }
    }
}
