<?php

namespace App\Http\Resources\Tender;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Organize\OrganizeResource;

class TenderLandingResource extends JsonResource
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
            'password' => $this->password,
            'title' => $this->title,
            'customer_name' => $this->customer_name,
            'opening_date' => $this->start_date,
            'invitation_link' => $this->invitation_link,
            'finish_date' => $this->end_date,
            'price' => $this->estimated,
            'price_file' => $this->estimated_price ? $this->estimated_price : $this->estimated_file,
            'type' => $this->type,
            'kind' => $this->kind,
            'guaranteed' => $this->guaranteed,
            'procedure' => $this->procedure,
            'is_ended' => ($this->end_date < date("Y-m-d H:i:s"))? true : false,
            'organizator' => $this->organizator ? $this->organizator->name : $this->customer_name,
            'region' => $this->region ? $this->region->name : 'Միջազգային',
            'is_old' => !(boolean)$this->is_new,
            'is_competition' => (boolean)$this->is_competition,
            'is_with_model' => (boolean)$this->is_with_model,
            'is_closed' => !(boolean)$this->is_closed,
            'is_from_manager' => (boolean)$this->manager_id,
            'is_mine' => $this->isMine(),
            'cpv_type' => $this->getCpvType(),
            'organize_type' => 
                $this->one_person_organize_id ? ($this->organizeOnePerson ? 'one_person' : 'itender') : false,
            'organize' => 
                $this->one_person_organize_id ? (
                $this->organizeOnePerson ? 
                new OrganizeResource($this->organizeOnePerson) : 
                new OrganizeResource($this->organizeItender))
                : null,
            'rows' => count($this->getCpv) ? TenderRowsResource::collection($this->getCpv) : TenderCategoryRowsResource::collection($this->getCategory),
        ];
    }
}
