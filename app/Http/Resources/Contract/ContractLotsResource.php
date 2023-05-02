<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Organize\OrganizeRowResource;

class ContractLotsResource extends JsonResource
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

            'view_id' => $this->view_id,
            'name' => $this->name,
            'specification' => $this->specification,
            'delivery_date' => $this->delivery_date,
            'payment_date' => $this->payment_date,
            'unit' => $this->unit,
    
            'has_failed_order' => $this->hasFailedOrder(),
            'active_orders_count' => $this->activeOrdersCount(),
            'row' => new OrganizeRowResource($this->organizeRow),
            'contract' => new ContractsMiniResource($this->contract),
        ];
    }
}
