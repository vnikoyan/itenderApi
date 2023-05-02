<?php
namespace App\Http\Requests\Settings\Guide;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class GuideStoreAndUpdateRequest extends FormRequest 
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        $rules =  [
            "file"          => "array",
            "title"          => "required|array",
            "description"    => "required|array",
        ];
        foreach (Language::getLanguages(Language::STATUS_ACTIVE) as $value){
            $rules["file.".$value->code] = 'mimes:pdf,xls,doc,docx,pptx,pps,jpeg,bmp,png|max:20000';
            $rules["title.".$value->code] = 'required';
            $rules["description.".$value->code] = 'required';
        }
        return $rules;
    }

}