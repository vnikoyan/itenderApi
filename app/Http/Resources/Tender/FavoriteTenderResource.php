<?php

namespace App\Http\Resources\Tender;

use App\Http\Resources\Organize\OrganizeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteTenderResource extends JsonResource
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
            'id' => $this->tender->id,
            'password' => $this->tender->password,
            'title' => $this->tender->title,
            'customer_name' => $this->tender->customer_name,
            'opening_date' => $this->tender->start_date,
            'invitation_link' => $this->tender->invitation_link,
            'finish_date' => $this->tender->end_date,
            'is_competition' => $this->tender->is_competition,
            'is_ended_electronic_link' => $this->tender->isEndedElectronicLink(),
            'price' => $this->tender->estimated,
            'type' => $this->tender->type,
            'price_file' => $this->tender->estimated_price ? $this->tender->estimated_price : $this->tender->estimated_file,
            'type' => $this->tender->type,
            'procedure_type' => $this->tender->type_name,
            'kind' => $this->tender->kind,
            'guaranteed' => $this->tender->guaranteed,
            'procedure' => $this->tender->procedure,
            'tender_state_id' => $this->tender->tender_state_id,
            'tender_announcements_count' => $this->tender->tender_state_id ? count($this->tender->tenderAnnouncements) : 1,
            'announcements_count' => count($this->tender->announcements),
            'is_ended' => ($this->tender->end_date < date("Y-m-d H:i:s"))? true : false,
            'organizator' => $this->tender->organizator ? $this->tender->organizator->name : $this->tender->customer_name,
            'region' => $this->tender->region ? $this->tender->region->name : 'Միջազգային',
            'is_old' => !(boolean)$this->tender->is_new,
            'is_closed' => !(boolean)$this->is_closed,
            'viewed' => $this->tender->isViewed(),
            'cpv_type' => $this->tender->getCpvType(),
            'is_favorite' => $this->tender->isFavorite(),
            'is_mine' => $this->tender->isMine(),
            'organize_type' => 
                $this->tender->one_person_organize_id ? ($this->tender->organizeOnePerson ? 'one_person' : 'itender') : false,
            'organize' => 
                $this->tender->one_person_organize_id ? (
                $this->tender->organizeOnePerson ? 
                new OrganizeResource($this->tender->organizeOnePerson) : 
                new OrganizeResource($this->tender->organizeItender))
                : null,
            'rows' => count($this->tender->getCpv) ? TenderRowsResource::collection($this->tender->getCpv) : TenderCategoryRowsResource::collection($this->tender->getCategory),
            "is_new_beneficiari" => (boolean)$this->tender->beneficiari,

        ];
    }
}
