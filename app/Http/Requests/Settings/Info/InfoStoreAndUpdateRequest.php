<?php
namespace App\Http\Requests\Settings\Info;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class InfoStoreAndUpdateRequest extends FormRequest 
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        $rules =  [
            'order'          => 'required|integer',
            "title"          => "required|array",
            "description"    => "required|array",
        ];
        foreach (Language::getLanguages(Language::STATUS_ACTIVE) as $value){
            $rules["title.".$value->code] = 'required';
            $rules["description.".$value->code] = 'required';
        }
        return $rules;
    }

}