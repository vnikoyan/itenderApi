<?php

namespace App\Http\Resources\Participant;

use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
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
            'name' => $this->participant[0]->name,
            'email' => $this->participant[0]->email,
            'phone' => $this->participant[0]->phone,
            'address' => $this->participant[0]->address,
            'tin' => $this->participant[0]->tin,
            'account_number' => $this->account_number,
            'bank' => $this->bank,
            'director' => $this->director,
            'contract_document' => $this->signed_contract_hy, 
        ];
    }
}
