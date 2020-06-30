<?php

namespace App\Http\Resources;
use App\User;
use App\Profile;
use App\Loan;

use Illuminate\Http\Resources\Json\Resource;

class GuarantorCollecion extends Resource
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
            'avatar' => Profile::where('UserId',$this->guarantor)->value('Avatar'),
            'name' => User::where('id',$this->guarantor)->value('FirstName')." ". User::where('id',$this->guarantor)->value('LastName'),
            'nationalId' => User::where('id',$this->guarantor)->value('NationalID'),
            'amount' => $this->amount,
            'date' => $this->created_at,
            'total' => Loan::where('id',$this->loanId)->value('guaranteedAmount'),


        ];
    }
}
