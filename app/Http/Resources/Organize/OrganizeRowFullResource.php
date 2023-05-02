<?php

namespace App\Http\Resources\Organize;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizeRowFullResource extends JsonResource
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
            'count' => $this->count,
            'supply' => $this->supply,
            'supply_date' => $this->supply_date,
            'is_main_tool' => $this->is_main_tool,
            'done_negotiations' => $this->done_negotiations,
            'is_collateral_requirement' => $this->is_collateral_requirement,
            'is_product_info' => $this->is_product_info,
            'winner_lot_trademark' => $this->winner_lot_trademark,
            'winner_lot_brand' => $this->winner_lot_brand,
            'winner_lot_manufacturer' => $this->winner_lot_manufacturer,
            'winner_lot_specification' => $this->winner_lot_specification,
            'is_from_outside' => $this->is_from_outside,
            'shipping_address' => $this->shipping_address,
            'financial_classifier_id' => $this->procurementPlan->details[0]->financial_classifier_id,
            'classifier_id' => $this->procurementPlan->details[0]->classifier_id,
            'financial_classifier' => $this->procurementPlan->details[0]->financial_classifier,
            'classifier' => $this->procurementPlan->details[0]->classifier,
            'unit_amount' => $this->procurementPlan->details[0]->unit_amount,
            'type' => $this->procurementPlan->details[0]->type,
            'is_condition' => $this->procurementPlan->is_condition,
            'condition_type' => $this->procurementPlan->condition_type,
            'unit' => $this->procurementPlan->unit,
            'cpv_type' => $this->procurementPlan->unit,
            'cpv_drop' => $this->procurementPlan->cpv_drop,
            'cpv_id' => $this->is_from_outside ? $this->procurementPlan->cpvOutside->id : $this->procurementPlan->cpv->id,
            'cpv_name' => $this->is_from_outside ? $this->procurementPlan->cpvOutside->name : $this->procurementPlan->cpv->name,
            'cpv_code' => $this->is_from_outside ? $this->procurementPlan->cpvOutside->code : $this->procurementPlan->cpv->code,
            'procurementPlan' => $this->procurementPlan,
            'participants' => $this->participants,
            'winner' => $this->winner,
            'participantsList' => $this->participantsList(),
            'plan_specifications' => $this->procurementPlan->specifications
        ];
    }
}
