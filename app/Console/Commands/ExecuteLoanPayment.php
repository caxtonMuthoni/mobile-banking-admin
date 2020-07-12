<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TransactionController;
use App\Loan;
use App\Account;
use App\User;
use App\Guarantor;
use App\LoanPayment;
use DateTime;

class ExecuteLoanPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute:loanPayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pay all loans that have reached payment date';

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
        $loans = Loan::where([['status','active'],['isProcessed',true],['guaranteeStatus',true]])->get();
        foreach($loans as $loan){
           $account = Account::where('CustomerID',$loan->userId)->first();
           $user = User::find($loan->userId);
           $executionDate = new DateTime($loan->nextPayment);
           $today = new DateTime('now'); 
           $installment = $loan->installment;
           $originalBal = $account->CurrentBalance;
           $isComplete = false;

           $remainder = $loan->totalRepayable - $loan->paidAmount;
           if($remainder <= $installment){
               $installment = $remainder;
               $isComplete = true;
           }


           $diff = $executionDate->diff($today)->format('%d');
           
           if($diff <= 0){
               $accountBal = $account->CurrentBalance;
               if ($accountBal > 0) {
                if($accountBal >= $installment){
                  $account->CurrentBalance -= $installment;
                }
                else{
                    $installment = $accountBal;
                    $account->CurrentBalance -= $installment;
                }
                
                if($account->save()){
                    $request = [
                        'TransactionType' => 'transfer',
                        'TransactionDescription' => "Loan repayment",
                        'TransID' => random_int(100000,10000000000),
                        'UserId' => $account->CustomerID,
                        'AccountNumber' => $account->AccountNumber,
                        'MSISDN' => $user->PhoneNumber,
                        'FirstName' => $user->FirstName,
                        'MiddleName' => $user->MiddleName,
                        'LastName' => $user->LastName,
                        'TransAmount' => $installment,
                        'OrgAccountBalance' => $originalBal,
                        'CrtAccountBalance' => $account->CurrentBalance,
                    ];
                    $transaction =  new TransactionController;
                    $t = $transaction->transact($request);
                    $loanPayment = new LoanPayment;
                    $loanPayment->loanId = $loan->id;
                    $loanPayment->transactionId = $t->id;
                    $loanPayment->save();
                    $today->modify('+1 month');
                    if($isComplete){
                      $loan->status = 'complete';
                      $guarantors = Guarantor::where('loanId',$loan->id)->get();
                      foreach($guarantors as $guarantor){
                          $guarantor->status = false;
                          $guarantor->save();
                      }
                    }
                    $nextDate = $today->format('Y-m-d h:i:s');
                    $loan->nextPayment = $nextDate;
                    $loan->paidAmount += $installment;
                    $loan->save();
                }
               }
           }

        }
        
    }
}
