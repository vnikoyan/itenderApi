<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractOrdersResource extends JsonResource
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
            'status' => $this->status,
            'index' => $this->index(),
            'invoice_number' => $this->invoice_number,
            'is_full' => (boolean) $this->invoice_number,

            'dispatch_date' => $this->dispatch_date,
            'discharge_date' => $this->discharge_date,
            'completion_actual_date' => $this->completion_actual_date,

            'contract' => new ContractsMiniResource($this->contract),
            'lots' => ContractOrderLotsResource::collection($this->lots),
        ];
    }
}
