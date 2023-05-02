<?php

namespace App\Http\Resources\Tender;

use App\Http\Resources\Organize\OrganizeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TenderResource extends JsonResource
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
            'participants_count' => $this->participants_count,
            'price' => $this->estimated,
            'price_file' => $this->estimated_price ? $this->estimated_price : $this->estimated_file,
            'type' => $this->type,
            'procedure_type' => $this->type_name,
            'kind' => $this->kind,
            'guaranteed' => $this->guaranteed,
            'procedure' => $this->procedure,
            'tender_state_id' => $this->tender_state_id,
            'tender_announcements_count' => $this->tender_announcements_count,
            'announcements_count' => $this->announcements_count,
            'region' => $this->region ? $this->region->name : 'Միջազգային',
            'is_old' => (boolean)!$this->is_new,
            'is_closed' => (boolean)!$this->is_closed,
            'is_with_model' => (boolean)$this->is_with_model,
            'is_competition' => (boolean)$this->is_competition,
            "is_new_beneficiari" => (boolean)$this->beneficiari,
            'rows' => $this->getCpvDemo ? TenderRowsDemoResource::collection($this->getCpvDemo) : '',
            'cpv_type' => $this->getCpvType(),
            'viewed' => $this->isViewed(),
            'is_ended' => ($this->end_date < date("Y-m-d H:i:s")) ? true : false,
            'organizator' => $this->organizator ? $this->organizator->name : $this->customer_name,
            'is_ended_electronic_link' => $this->isEndedElectronicLink(),
            'is_favorite' => $this->isFavorite(),
            'is_mine' => $this->isMine(),
            'organize_type' => 
                $this->one_person_organize_id ? ($this->organizeOnePerson ? 'one_person' : 'itender') : false,
            'organize' => 
                $this->one_person_organize_id ? (
                $this->organizeOnePerson ? 
                new OrganizeResource($this->organizeOnePerson) : 
                new OrganizeResource($this->organizeItender))
                : null,
        ];
    }
}
