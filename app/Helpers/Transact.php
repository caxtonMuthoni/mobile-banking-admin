<?php

namespace App\Helpers;
use App\Transaction;

class Transact{

    public function transact($TransactionType,$TransID,$TransAmount,$UserId,$AccountNumber,$MSISDN,$FirstName,$MiddleName,$LastName,$OrgAccountBalance,$CrtAccountBalance){
       
        $transaction = new Transaction;
                $transaction->TransactionType = $TransactionType ;
                $transaction->TransID = $TransID ;
                $transaction->TransAmount = $TransAmount ;
                $transaction->UserId = $UserId ;
                $transaction->AccountNumber = $AccountNumber ;
                $transaction->MSISDN = $MSISDN ;
                $transaction->FirstName = $FirstName ;
                $transaction->MiddleName = $MiddleName ;
                $transaction->LastName = $LastName ;
                $transaction->OrgAccountBalance = $OrgAccountBalance ;
                $transaction->CrtAccountBalance = $CrtAccountBalance ;
                return $transaction->save();

    }

}