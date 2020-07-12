<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Account;

class GroupAccountResource extends Resource
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
            'id' => $this->id,
            'account_id' => $this->accountId,
            'status' => $this->status,
            'role' => $this->role,
            'account_number' => Account::where('id',$this->accountId)->value('AccountNumber'),
            'account_name' => Account::where('id',$this->accountId)->value('AccountName'),
            'current_balance' => Account::where('id',$this->accountId)->value('CurrentBalance')
        ];
    }
}
