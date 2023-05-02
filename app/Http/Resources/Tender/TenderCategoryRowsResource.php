<?php

namespace App\Http\Resources\Tender;

use Illuminate\Http\Resources\Json\JsonResource;

class TenderCategoryRowsResource extends JsonResource
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
            'id' => $this->category->id,
            'name' => $this->category->name,
        ];
    }
}
