<?php
namespace App\Http\Requests\Settings\Units;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class UnitsStoreAndUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        $rules =  [
            'order'          => 'required|integer',
            "units"          => "required|array",
        ];
        foreach (Language::getLanguages(Language::STATUS_ACTIVE) as $value){
            $rules["units.".$value->code] = 'required';
        }
        return $rules;
    }

}