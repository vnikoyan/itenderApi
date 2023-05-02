<?php
namespace App\Http\Requests\User\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserStateUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            'name'          => 'required',
            'name_ru'          => 'required',
//            'phone'          => 'required',
//            'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user_state)],
        ];
    }


}
