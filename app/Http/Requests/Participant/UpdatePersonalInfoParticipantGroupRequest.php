<?php


namespace App\Http\Requests\Participant;


use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonalInfoParticipantGroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * UpdateParticipantRequest CreateSelectedParticipantRequest
     * @return array
     */
    public function rules(){
        return [
            'account_number'                    => 'required',
            'bank'                           => 'required',
            'director'                          => 'required',
        ];
    }
}
