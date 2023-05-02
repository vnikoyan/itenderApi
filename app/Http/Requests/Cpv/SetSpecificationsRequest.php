<?php
namespace App\Http\Requests\Cpv;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class SetSpecificationsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){

        $rules =  [
            // 'user_id' => 'required|integer',
        ];
        foreach (Language::getLanguages(Language::STATUS_ACTIVE) as $value){
            $rules["description.".$value->code] = 'required';
        }
        return $rules;

    }

}