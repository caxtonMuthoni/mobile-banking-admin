<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use App\User;
use App\Loan;
use DateTime;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getAlias($id){
        $loan = Loan::find($id);
        $starDate = new DateTime($loan->created_at);
        $nextPayment = new DateTime($loan->nextPayment);
        $today =  new DateTime('now');
        $installment = $loan->installment;
        $paidAmount = $loan->paidAmount;

        $months = round(($today->diff($starDate)->days)/30);
        $totalPayment = $months * $installment; // What should have paid
         if($loan->status == 'complete'){
             return 'complete';
         }

        if(($paidAmount - $totalPayment) < 0){
            return round($paidAmount - $totalPayment,2);
        }
        if(($paidAmount - $totalPayment) == 0){
            return 0;
        }
        return '-'.round($paidAmount - $totalPayment,2);
        

        
    }
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "borrower" => User::where('id',$this->userId)->value('FirstName').' '.User::where('id',$this->userId)->value('LastName'),
            "userId" => $this->userId,
            "loanId" => $this->loanId,
            "loanType" => $this->loanType,
            "borrowedAmount" => $this->borrowedAmount,
            "interest" => $this->interest,
            "period" =>$this->period,
            "installment" => $this->installment,
            "startDate" => $this->created_at,
            "nextPayment" => $this->nextPayment,
            "dueDate" => $this->dueDate,
            "alias" => LoanResource::getAlias($this->id),
            "totalRepayable" => $this->totalRepayable,
            "paidAmount" => $this->paidAmount,
            "guarantorAmount" => $this->guarantorAmount,
            "guaranteedAmount" => $this->guaranteedAmount,
            "guaranteeStatus" => $this->guaranteeStatus,
            "isProcessed" => $this->isProcessed,
            "status" => $this->status,
            
        ];
    }
}
