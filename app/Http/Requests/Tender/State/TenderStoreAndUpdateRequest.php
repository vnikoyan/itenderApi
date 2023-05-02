<?php
namespace App\Http\Requests\Tender\State;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class TenderStoreAndUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        $rules =  [
            'start_date'          => 'required|date',
            "end_date"            => "required|date",
            // "cpv"                 => "required|array",
            "organizator"         => "required",
            "guaranteed"         => "required",
            // "estimated_file"      => "required|mimes:doc,pdf,docx,zip",
        ];
        // foreach (Language::getLanguages(Language::STATUS_ACTIVE) as $value){
        //     $rules["title.".$value->code] = 'required';
        // }
        return $rules;
    }

}
