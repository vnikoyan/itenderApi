<?php
namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AdminStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        return [
            'name'          => 'required|max:30',
            'email'         => 'required|email|unique:admins,email',
            'user_name'     => 'required|unique:admins,user_name',
            'password'      => 'required|confirmed|min:8'
        ];
    }

}