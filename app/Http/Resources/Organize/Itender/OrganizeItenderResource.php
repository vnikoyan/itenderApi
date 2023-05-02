<?php

namespace App\Http\Resources\Organize\Itender;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizeItenderResource extends JsonResource
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
            'name' => $this->name,
            'name_ru' => $this->name_ru,
            'code' => $this->code,
            'code_ru' => $this->code_ru,
            'calendar_schedule' => $this->calendar_schedule,
            'confirm' => $this->confirm,
            'contract_html_hy' => $this->contract_html_hy,
            'cpv_type' => $this->cpv_type,
            'create_contract' => $this->create_contract,
            'decision_number' => $this->decision_number,
            'is_with_condition' => false,
            'least_work_percent' => $this->least_work_percent,
            'opening_date_time' => $this->opening_date_time,
            'other_requirements' => $this->other_requirements,
            'procurement' => $this->procurement,
            'protocol_presentation_deadline' => $this->protocol_presentation_deadline,
            'protocols_copy_number' => $this->protocols_copy_number,
            'publication' => $this->publication,
            'publicize' => $this->publicize,
            'purchase_schedule' => $this->purchase_schedule,
            'payment_schedule_text' => $this->payment_schedule_text,
            'send_to_all_participants' => $this->send_to_all_participants,
            'shipping_address' => $this->shipping_address,
            'translations' => $this->translations,
            'winner_by_lots' => $this->winner_by_lots,
            'winner_user_price' => $this->winner_user_price,
            'winner_user_price_word' => $this->winner_user_price_word,
            'send_date' => $this->send_date,
            
            'organize_rows' => $this->organizeRows,
            'participants' => $this->participants(),
            'work_type' => '',
            'lots' => '',
            'winners' => '',
        ];
    }
}
