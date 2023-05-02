<?php
namespace App\Http\Requests\User\Package;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class PackageStateAddEditRequest extends FormRequest 
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        return [
            'price'         => 'required|numeric',
            'name'          => 'required',

        ];
    }

}