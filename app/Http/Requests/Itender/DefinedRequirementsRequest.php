<?php
namespace App\Http\Requests\Itender;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class DefinedRequirementsRequest extends FormRequest 
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        $rules =  [ 
            'title'          => 'required',
            'order'          => 'required|numeric',
        ];
 
        return $rules;
    }

}