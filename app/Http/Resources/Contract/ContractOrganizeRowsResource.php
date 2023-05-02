<?php

namespace App\Http\Resources\Contract;

use App\Http\Resources\Organize\OrganizeRowResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractOrganizeRowsResource extends JsonResource
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
            'price' => $this->total_price,
            'price_unit' => $this->price_unit,
            'ordered' => $this->ordered,
            'supplied' => $this->supplied,
            'available' => $this->available,

            'view_id' => $this->organizeRow ? $this->organizeRow->view_id : '',
            'name' => $this->organizeRow ? ($this->organizeRow->is_from_outside ? $this->organizeRow->procurementPlan->cpvOutside->name : $this->organizeRow->procurementPlan->cpv->name) : '',
            'specification' => $this->organizeRow ? ($this->organizeRow->procurementPlan->specifications->description) : '',
            'delivery_date' => $this->organizeRow ? ($this->deliveryDate()) : '',
            'payment_date' => $this->payment_date,
            'unit' => $this->organizeRow ? ($this->organizeRow->procurementPlan->unit) : '',
    
            'has_failed_order' => $this->organizeRow ? ($this->hasFailedOrder()) : '',
            'active_orders_count' => $this->organizeRow ? ($this->activeOrdersCount()) : '',
            'row' => $this->organizeRow ? (new OrganizeRowResource($this->organizeRow)) : '',
            'contract' => new ContractsMiniResource($this->contract),
        ];
    }
}
