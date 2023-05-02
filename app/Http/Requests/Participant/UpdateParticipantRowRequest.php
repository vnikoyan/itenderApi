<?php


namespace App\Http\Requests\Participant;


use Illuminate\Foundation\Http\FormRequest;

class UpdateParticipantRowRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     * UpdateParticipantRequest
     * @return array
     */
    public function rules(){
        return [
            'organize_row_id' => 'nullable|integer',
            'row_group_id'    => 'nullable|integer',
            'cost'            => 'nullable|numeric',
            'profit'          => 'nullable|numeric',
            'value'           => 'nullable|numeric',
            'vat'             => 'nullable|integer',
        ];
    }

}
