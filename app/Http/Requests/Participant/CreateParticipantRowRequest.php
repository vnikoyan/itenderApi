<?php


namespace App\Http\Requests\Participant;


use Illuminate\Foundation\Http\FormRequest;

class CreateParticipantRowRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * UpdateParticipantRequest
     * @return array
     */
    public function rules(){
        return [
            'organize_row_id' => 'required|integer',
            // 'row_group_id'    => 'required|integer',
            // 'profit'          => 'required|numeric',
            'cost'            => 'required|numeric',
            'value'           => 'required|numeric',
            'vat'             => 'required|integer',
        ];
    }

}
