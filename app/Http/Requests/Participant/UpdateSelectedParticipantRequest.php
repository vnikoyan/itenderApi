<?php


namespace App\Http\Requests\Participant;


use Illuminate\Foundation\Http\FormRequest;

class UpdateSelectedParticipantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array nullable
     */
    public function rules(){
        return [
            'organize_row_id'               => 'nullable|integer',
            'participant_group_id'          => 'nullable|integer',
            'bank'                          => 'nullable',
            'bank.hy'                       => 'nullable',
            'bank.ru'                       => 'nullable',
            'hh'                            => 'nullable',
            'name'                          => 'nullable',
            'manufacturer_name'             => 'nullable',
            'country_of_origin'             => 'nullable',
        ];
    }
}
