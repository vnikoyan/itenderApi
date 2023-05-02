<?php

namespace App\Http\Resources\Organize\Itender;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizeWinnerUserResource extends JsonResource
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
            'organisation' => $this->organisation,
            'name' => $this->name.' '.$this->organisation->company_type,
            'won_lots' => $this->won_lots,
        ];
    }
}
