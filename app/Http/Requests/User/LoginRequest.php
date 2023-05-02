<?php

// Define the namespace
namespace App\Http\Requests\User;

// Include any required classes, interfaces etc...
use App\Http\Requests\AbstractRequest;


class LoginRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'     => 'required',
            'password'  => 'required'
        ];
    }
}
