<?php
namespace App\Http\Requests\Cpv;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class CpvRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        return [
            'file'           => 'required|mimes:xlsx,xls,doc,docx,ppt,pptx,ods,odt,odp|max:50000',
        ];
    }

}