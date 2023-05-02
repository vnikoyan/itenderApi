<?php


namespace App\Http\Requests\Procurement;


use Illuminate\Foundation\Http\FormRequest;

class ProcurementUploadFileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            'file'          => 'required',
        ];
    }

}