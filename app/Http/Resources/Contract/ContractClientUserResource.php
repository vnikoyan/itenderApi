<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractClientUserResource extends JsonResource
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
            'name' => $this->organisation->id_card_number ? $this->organisation->name : ('«'.$this->organisation->name.'» '.$this->organisation->company_type),
            'email' => $this->email,
            'address' => $this->organisation->region.', '.$this->organisation->city.', '.$this->organisation->address,
            'phone' => $this->phone,
            'organisation_address' => $this->organisation->region.' '.$this->organisation->city.' '.$this->organisation->address,
            'account_number' => $this->organisation->bank_account,
            'bank' => $this->organisation->bank_name,
            'tin' =>  $this->organisation->tin,
            'director' => $this->organisation->director_name,
        ];
    }
}
