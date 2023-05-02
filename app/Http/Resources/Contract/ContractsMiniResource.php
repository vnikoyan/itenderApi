<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Participant\ParticipantResource;
use App\Http\Resources\User\UserResource;


class ContractsMiniResource extends JsonResource
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
            'type' => $this->type,
            'name' => $this->name,
            'sign_date' => $this->sign_date,
            'organize_type' => $this->organizeType(),
            'from_application' => (boolean) $this->from_application,
            'from_implement_immediately' => (boolean) $this->from_implement_immediately,
            'client' => $this->from_application ? new ContractClientResource($this->client) : new ContractClientUserResource($this->clientUser),
            'participant' =>  $this->participant ? new ParticipantResource($this->participant) : new UserResource($this->participantUsers),
        ];
    }
}
