<?php

namespace App\Http\Resources\Organize;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Psy\Test\FakeShell;

class OrganizeResource extends JsonResource
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
            'code' => $this->code,
            'type' => $this->is_one_person ? 'one_person' : ($this->is_itender ? 'itender' : 'competitive'),
            'name' => $this->name,
            'cpv_type' => $this->cpv_type,
            'client' => new UserResource($this->user),
            'customer' => $this->user,
            'customer_organisation' => isset($this->user->organisation) ? $this->user->organisation->name : '',
            'customer_id' => isset($this->user->id) ?$this->user->id : '',
            'winner_by_lots' => $this->winner_by_lots,
            'shipping_address' => $this->shipping_address,
            'other_requirements' => $this->other_requirements,
            'additional_file' => $this->additional_file,
            'purchase_schedule' => $this->purchase_schedule,
            'payment_schedule_text' => $this->payment_schedule_text,
            'delivery_type' => $this->delivery_type,
            'cancel_reason' => $this->cancel_reason === 'not_requirement_purchase' ? 'դադարել է գոյություն ունենալ գնման պահանջը' : 'անհրաժեշտություն է առաջացել փոփոխել կազմակերպված մրցույթի պայմանները',
            'is_canceled' => (boolean) $this->is_canceled,
            'itender_edited' => (boolean) $this->itender_edited,
            'itender_type' => $this->itender_type,
            'report_document' => $this->report_document,
            'implementation_deadline' => $this->is_itender ? $this->implementation_deadline : false,
            'winners' => $this->is_one_person || $this->is_itender ? $this->winners() : false,
            'winner_user_price' => $this->winner_user_price,
            'finish_date' => $this->opening_date_time,
            'send_date' => $this->send_date,
            'status' => $this->status(),
            'organize_rows' => OrganizeRowResource::collection($this->organizeRows),
            // 'organize_rows_all' => $this->organizeRows,
            'contract' => $this->contract_html_hy,
        ];
    }
}
