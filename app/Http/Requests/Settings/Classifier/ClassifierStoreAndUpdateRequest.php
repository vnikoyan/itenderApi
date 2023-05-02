<?php
namespace App\Http\Requests\Settings\Classifier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class ClassifierStoreAndUpdateRequest extends FormRequest 
{
    /**Classifier\ClassifierStoreAndUpdateRequest
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        $rules =  [ 
            'title'         => 'required',
            'code'          => 'required',
            'cpv_id'        => 'required',
        ];
        return $rules;
    }

}