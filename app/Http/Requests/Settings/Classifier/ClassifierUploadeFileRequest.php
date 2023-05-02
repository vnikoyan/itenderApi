<?php
namespace App\Http\Requests\Settings\BlackList;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class BlackListUploadeFileRequest extends FormRequest 
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        $rules =  [ 
            'file'           => 'mimes:xlsx,doc,docx,ppt,pptx,ods,odt,odp|max:50000',
        ];
 
        return $rules;
    }

}