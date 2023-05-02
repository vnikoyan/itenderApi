<?php


namespace App\Http\Requests\User;
use App\Http\Requests\AbstractRequest;


class CreateResponsibleMembersRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name"       => "required",
            "position"    => "required",
        ];
    }
}