<?php
namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AdminUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        return [
            'name'          => 'required|max:30',
            // 'email'         => ['required', 'email', Rule::unique('admins', 'email')->ignore($this->admin)],
            // 'user_name'     => ['required', Rule::unique('admins', 'user_name')->ignore($this->admin)],
            // 'password'      => 'max:255|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
            'password'      => 'confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/|nullable'

        ];
    }

}