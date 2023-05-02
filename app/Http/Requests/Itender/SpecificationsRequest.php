<?php
namespace App\Http\Requests\Itender;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class SpecificationsRequest extends FormRequest 
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        foreach (Language::getLanguages(Language::STATUS_ACTIVE) as $value){
            $rules["description.".$value->code] = 'required';
        }
        return $rules;
    }

}