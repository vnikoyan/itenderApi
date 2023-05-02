<?php

// Define the namespace
namespace App\Http\Requests\User\User;

// Include any required classes, interfaces etc...
use App\Http\Requests\AbstractRequest;
use App\Models\Translation\Language;

class CreateUserRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */

    public function rules()
    {
        $rules =  [
            'bank_account'  => 'required',
            'email'         => 'required|email|max:128|email',
            'username'      => 'required|max:128|unique:users,username',
            'phone'         => 'required',
            'password'      => 'required|min:6|max:255|confirmed'
        ];

        foreach (Language::getLanguages(Language::STATUS_ACTIVE) as $value){
            $rules["name.".$value->code] = 'required';
            $rules["region.".$value->code] = 'required';
            $rules["city.".$value->code] = 'required';
            $rules["address.".$value->code] = 'required';
            $rules["bank_name.".$value->code] = 'required';
            // $rules["company_type.".$value->code] = 'required';
            // $rules["nickname.".$value->code] = 'required';
            // $rules["director_name.".$value->code] = 'required';
        }
        return $rules;

    }


}
