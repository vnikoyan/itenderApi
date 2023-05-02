<?php
namespace App\Http\Requests\Settings\CoWorkers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class CoWorkersStoreAndUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        $rules =  [
            'user_id'          => 'required|integer',
            "address"          => "required",
            "website"          => "required",
            "website"          => "required",
            "cpv"              => "required",
            // 'image'            => 'required|mimes:jpeg,jpg,png,gif|max:40000',
            'image'            => 'nullable|mimes:jpeg,jpg,png,gif|max:40000',
        ];
        return $rules;
    }

}