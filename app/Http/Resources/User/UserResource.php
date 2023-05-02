<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'organisation_name' => $this->organisation->name,
            'email' => $this->email,
            'user_name' => $this->name,
            'company_type' => $this->organisation ? $this->organisation->company_type : '',
            'address' => $this->organisation->region.', '.$this->organisation->city.', '.$this->organisation->address,
            'phone' => $this->phone,
            'vat_payer' => $this->vat_payer_type !== 'not_payer',
            'organisation_address' => $this->organisation->region.' '.$this->organisation->city.' '.$this->organisation->address,
            'account_number' => $this->organisation->bank_account,
            'bank' => $this->organisation->bank_name,
            'tin' =>  $this->organisation->tin,
            'id_card_number' => $this->organisation->id_card_number,
            'passport_serial_number' => $this->organisation->passport_serial_number,
            'passport_given_at' => $this->organisation->passport_given_at,
            'passport_from' => $this->organisation->passport_from,
            'passport_valid_until' => $this->organisation->passport_valid_until,
        ];
    }
}
