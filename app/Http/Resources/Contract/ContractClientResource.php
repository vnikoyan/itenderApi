<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractClientResource extends JsonResource
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
            'name' => $this->name,
            'organisation_name' => $this->name,
            'email' => 'not found',
            'address' => $this->address,
            'phone' => 'not found',
            'organisation_address' => $this->address,
            'account_number' => $this->account_number,
            'bank' => $this->bank,
            'tin' => $this->tin,
            'director' => $this->director,
        ];
    }
}
