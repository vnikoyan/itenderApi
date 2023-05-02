<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Participant\ParticipantResource;
use App\Http\Resources\Organize\OrganizeResource;
use App\Http\Resources\User\UserResource;


class ContractsResource extends JsonResource
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
            'sign_date' => $this->sign_date,
            'price' => $this->price,
            'type' => $this->type,
            'is_sign' => (boolean) $this->is_sign,
            'is_full' => (boolean) $this->name,
            'from_application' => (boolean) $this->from_application,
            'from_implement_immediately' => (boolean) $this->from_implement_immediately,
            'organize' => new OrganizeResource($this->organizeData()),
            'participant' => new ParticipantResource($this->participant),
            'client' => $this->from_application ? new ContractClientResource($this->client) : new ContractClientUserResource($this->clientUser),
            'lots' => $this->from_application ? ContractLotsResource::collection($this->lots) : ContractOrganizeRowsResource::collection($this->lots),
            // 'lots' => ContractLotsResource::collection($this->lots),
        ];
    }
}
