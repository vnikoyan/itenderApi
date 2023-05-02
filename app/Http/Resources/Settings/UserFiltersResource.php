<?php

namespace App\Http\Resources\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFiltersResource extends JsonResource
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
            'guaranteed' => json_decode($this->guaranteed),
            'isElectronic' => json_decode($this->isElectronic),
            'organizator' => json_decode($this->organizator),
            'procedure' => json_decode($this->procedure),
            'region' => json_decode($this->region),
            'status' => json_decode($this->status),
            'type' => json_decode($this->type),
        ];
    }
}
