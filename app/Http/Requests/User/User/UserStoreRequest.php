<?php
namespace App\Http\Requests\User\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            'name'          => 'required|max:64',
            // 'phone'         => 'required|max:64|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'phone'         => 'required|max:64|regex:/(0)[0-9]{8}/',
            'status'        => 'required',
            'tin'           => 'required|numeric|min:0|not_in:0|unique:users,tin',
            'email'         => 'required|email',
            'password'      => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/'
        ];
    }
}
