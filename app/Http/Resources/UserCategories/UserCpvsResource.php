<?php

namespace App\Http\Resources\UserCategories;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Organize\OrganizeResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Organize\OrganizeRowResource;

class UserCpvsResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->code,
            // 'statistics_count' => count($this->cpv->cpvStatistics)
            // 'used_count' => $this->tenderStateRow->count(),
            'used_count' => $this->tender_state_row_count,
        ];
    }
}
