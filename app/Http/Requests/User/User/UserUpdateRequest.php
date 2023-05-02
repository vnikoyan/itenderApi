<?php
namespace App\Http\Requests\User\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            'name'          => 'required',
            'phone'         => 'required',
            'status'        => 'required',
            'email'         => 'required|email',
            'password'      => 'confirmed|min:8|nullable'
        ];
    }

}
