<?php

namespace App\Http\Resources\Organize;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizeRowResource extends JsonResource
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
            'view_id' => $this->view_id,
            'cpv_name' => $this->is_from_outside ? $this->procurementPlan->cpvOutside->name : $this->procurementPlan->cpv->name,
            'cpv_unit' => $this->is_from_outside ? $this->procurementPlan->cpvOutside->unit : $this->procurementPlan->cpv->unit,
            'cpv_code' => $this->is_from_outside ? $this->procurementPlan->cpvOutside->code : $this->procurementPlan->cpv->code,
            'offers' => $this->participants,
            'organize' => $this->organize,
            'participants' => $this->participantsList(),
            'participants_list' => $this->participants,
            'won_offer' => $this->offer,
            'winner' => $this->winner,
            'won_lot_id' => $this->won_lot_id,
            'unit_amount' => $this->procurementPlan->details[0]->unit_amount,
            'count' => $this->count,
            'type' => +$this->procurementPlan->details[0]->type,
            'specifications' => $this->procurementPlan->specifications->description
        ];
    }
}
