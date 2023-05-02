<?php


namespace App\Http\Requests\User;

use App\Http\Requests\AbstractRequest;

class CreateUserResponsibleRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required',
            'password'      => 'required|min:6',
            'email'         => 'required|email',
            "members"       => "required|array",
            "members.*.name"       => "required",
            "members.*.position"       => "required",
        ];
    }
}