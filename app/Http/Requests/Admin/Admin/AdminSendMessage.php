<?php
namespace App\Http\Requests\Admin\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AdminSendMessage extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        return [
            'title'         => 'required',
            'text'    		=> 'required',
        ];
    }

}