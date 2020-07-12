<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\User;

class InvestResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
             "user"=>User::where('id',$this->userId)->value('FirstName').' '.User::where('id',$this->userId)->value('LastName'),
             "duration" => $this->duration,
             "termination_date" => $this->terminationDate,
             "interest" => $this->interest,
             "amount" => $this->amount,
             "status" => $this->status,
             "total_pay" => $this->totalPay,
             "created_at" => $this->created_at,
        ];
    }
}

/*  "id": 1,
        "userId": 1,
        "accountId": 1,
        "duration": 10,
        "terminationDate": "2021-05-06 12:06:39",
        "interest": 3,
        "totalPay": 6500,
        "created_at": "2020-07-06 12:06:39",
        "updated_at": "2020-07-06 12:06:39" */
