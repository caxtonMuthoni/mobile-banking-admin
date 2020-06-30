<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\User;

class TransactionCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transactionID' =>$this->TransID,
            'user' => User::where('id',$this->UserId)->value('FirstName')." ".User::where('id',$this->UserId)->value('LastName'),
            'phone' => $this->MSISDN,
            'description' => $this->TransactionDescription,
            'amount' => $this->TransAmount,
            'original_bal' => $this->OrgAccountBalance,
            'new_bal' => $this->CrtAccountBalance,
            'date' => $this->created_at
        ];
    }
}
