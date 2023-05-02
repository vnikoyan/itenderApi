<?php
namespace App\Http\Requests\Cpv;


use Illuminate\Foundation\Http\FormRequest;

class GetCpvByIdsRequest  extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'ids' => 'required',
            'ids.*' => 'required|integer',
        ];
    }

}
