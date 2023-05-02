<?php


namespace App\Http\Requests\User\User;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\GerupRoot; // imported in the FormRequest


class UserStateStoreDivisionsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            'c_name'          => 'required|max:64',
            'c_email'         => 'required|email',
            'c_username'         => 'required|unique:users,username',
            // 'c_type'          => ['required', new GerupRoot($this->route('org_id'))]
            'c_type'          => 'required'
        ];
    }

}
