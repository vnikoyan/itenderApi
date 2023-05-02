<?php

namespace App\Http\Resources\Organize\OnePerson;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizeWinnerParticipantResource extends JsonResource
{

    public function getRuField($data, $field){
        $curr_field = json_decode(json_encode($data), true)[$field];
        if($curr_field){
            return $curr_field['ru'];
        } else {
            return "";
        }
    }
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
            'suggestion' => $this->suggestion,
            'address' => count($this->participant) ? $this->participant[0]->address : '',
            'address_ru' => count($this->participant) ? $this->getRuField($this->participant[0], 'address') : '',
            'email' => count($this->participant) ? $this->participant[0]->email : '',
            'name' => count($this->participant) ? ($this->participant[0]->name ? $this->participant[0]->name : $this->participant[0]->first_name) : '',
            'name_ru' => count($this->participant) ? ($this->participant[0]->name ? $this->getRuField($this->participant[0], 'name') : $this->getRuField($this->participant[0], 'first_name')) : '',
            'phone' => count($this->participant) ? $this->participant[0]->phone : '',
            'tin' => count($this->participant) ? $this->participant[0]->tin : '',
            'date_of_submission' => count($this->participant) ? $this->participant[0]->date_of_submission : '',
            'bank' => $this->bank,
            'bank_account' => $this->account_number,
            'user_id' => $this->user_id,
            'director' => $this->director,
            'participant' => $this->participant,
            'user_datas' => [
                "bank" => count($this->lots) ? (($this->lots && $this->lots[0]->userInfo) ? $this->lots[0]->userInfo->organisation->bank_name : '') : '',
                "account_number" => count($this->lots) ? (($this->lots && $this->lots[0]->userInfo) ? $this->lots[0]->userInfo->organisation->bank_account : '') : '',
                "director" => count($this->lots) ? (($this->lots && $this->lots[0]->userInfo) ? ( $this->lots[0]->userInfo->organisation->id_card_number ? $this->lots[0]->userInfo->organisation->name : $this->lots[0]->userInfo->organisation->director_name ) : '') : '',
            ],
            'won_lots' => $this->wonLots,
            'lots' => $this->rows,
            'signed_contract_hy' => $this->signed_contract_hy,
            'signed_contract_ru' => $this->signed_contract_ru,
        ];
    }
}
