<?php

namespace App\Http\Resources\Tender;

use Illuminate\Http\Resources\Json\JsonResource;

class TenderRowsResource extends JsonResource
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
            'cpv_id' => (int) $this->cpv_id,
            'viewId' => (int) $this->view_id,
            'is_mine' => (bool) $this->is_mine,
            'estimated_price' => $this->estimated_price,
            'unit' => $this->unit,
            'count' => $this->count,
            'estimated_price' => $this->estimated_price,
            'name' => ($this->cpv_name ? $this->cpv_name : (gettype($this->cpv) === 'object' ? $this->cpv->name : '')),
            'code' => ($this->cpv_code ? $this->cpv_code : (gettype($this->cpv) === 'object' ? $this->cpv->code : '')),
        ];
    }
}
