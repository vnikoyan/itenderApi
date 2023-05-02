<?php

namespace App\Http\Resources\UserCategories;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Organize\OrganizeResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Organize\OrganizeRowResource;

class UserCategoriesResource extends JsonResource
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
            'code' => $this->category->code,
            'name' => $this->category->name,
            'type' => $this->category->code,
        ];
    }
}
