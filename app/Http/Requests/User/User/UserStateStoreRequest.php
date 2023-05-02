<?php
namespace App\Http\Requests\User\User;

use Illuminate\Foundation\Http\FormRequest;



class UserStateStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            'name'          => 'required|max:64',
            'phone'         => 'required|max:64|regex:/(0)[0-9]{8}/',
            'address'       => 'required',
            'status'        => 'required',
            'tin'           => 'required|unique:users,tin',
            'contract'      => 'mimes:pdf,xls,doc,docx,pptx,pps,jpeg,bmp,png|max:20000',
            'email'         => 'required|email',
            'password'      => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).+$/'
        ];
    }

}
