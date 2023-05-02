<?php
namespace App\Http\Requests\Settings\BlackList;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class BlackListStoreAndUpdateRequest extends FormRequest 
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        $rules =  [ 
            'name'         => 'required',
            'start_date'   => 'required',
            'end_date'     => 'required',
            'address'      => 'required',
            'info'         => 'required',
            'for_what'     => 'required',
        ];
 
        return $rules;
    }

}