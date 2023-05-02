<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Organize\OrganizeRowResource;

class ContractOrderLotsResource extends JsonResource
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
            'ordered' => $this->ordered,
            'contract' => $this->lot->contract,
            'lot_info' => $this->lot->contract->from_application ? new ContractLotsResource($this->lot) : new ContractOrganizeRowsResource($this->lot),
            // 'lot_info' => new ContractLotsResource($this->lot),
        ];
    }
}
