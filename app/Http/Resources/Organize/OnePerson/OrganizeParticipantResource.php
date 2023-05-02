<?php

namespace App\Http\Resources\Organize\OnePerson;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizeParticipantResource extends JsonResource
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
            'additional_file' => ($this->suggestion && $this->suggestion->additional_file) ? $this->suggestion->additional_file : '',
            'id' => $this->id,
            'address' => count($this->participant) ? $this->participant[0]->address : '',
            'email' => count($this->participant) ? $this->participant[0]->email : '',
            'name' => count($this->participant) ? ($this->participant[0]->name ? $this->participant[0]->name : $this->participant[0]->first_name) : '',
            'phone' => count($this->participant) ? $this->participant[0]->phone : '',
            'tin' => count($this->participant) ? $this->participant[0]->tin : '',
            'date_of_submission' => count($this->participant) ? $this->participant[0]->date_of_submission : '',
            'bank' => $this->bank,
            'bank_account' => $this->account_number,
            'director' => $this->director,
            'lots' => $this->lots,
            'participant' => $this->participant,
            // 'additional_file' => count($this->lots) ? (($this->lots && $this->lots[0]->userInfo) ? $this->lots[0]->userInfo->suggestions : '') : '',
            'user_datas' => [
                "bank" => count($this->lots) ? (($this->lots && $this->lots[0]->userInfo) ? $this->lots[0]->userInfo->organisation->bank_name : '') : '',
                "account_number" => count($this->lots) ? (($this->lots && $this->lots[0]->userInfo) ? $this->lots[0]->userInfo->organisation->bank_account : '') : '',
                "director" => count($this->lots) ? (($this->lots && $this->lots[0]->userInfo) ? ( $this->lots[0]->userInfo->organisation->id_card_number ? $this->lots[0]->userInfo->organisation->name : $this->lots[0]->userInfo->organisation->director_name ) : '') : '',
            ],
        ];
    }
}
