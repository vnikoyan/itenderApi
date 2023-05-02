<?php

namespace App\Http\Resources\Suggestions;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Organize\OrganizeResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Organize\OrganizeRowResource;

class SuggestionsResource extends JsonResource
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
            'is_favorite' => $this->isFavorite(),
            'is_itender' => $this->is_itender,
            'seen' => (boolean) $this->seen,
            'additional_file' => $this->additional_file,
            'client' => new UserResource($this->client),
            'organize' => $this->is_itender ? new OrganizeResource($this->organizeItender) : new OrganizeResource($this->organize),
        ];
    }
}
