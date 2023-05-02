<?php


namespace App\Http\Requests\Participant;


use Illuminate\Foundation\Http\FormRequest;

class CreateSelectedParticipantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * UpdateParticipantRequest UpdateSelectedParticipantRequest
     * @return array nullable
     */
    public function rules(){
        return [
            'organize_row_id'               => 'required|integer',
            'participant_group_id'          => 'required|integer',
            'bank'                          => 'required',
            'bank.hy'                       => 'required',
            'bank.ru'                       => 'required',
            'hh'                            => 'required',
            'name'                          => 'required',
            'manufacturer_name'             => 'required',
            'country_of_origin'             => 'required',
        ];
    }
}
