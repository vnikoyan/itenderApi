<?php


namespace App\Http\Requests\Participant;


use Illuminate\Foundation\Http\FormRequest;

class CreateParticipantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * UpdateParticipantRequest CreateSelectedParticipantRequest
     * @return array
     */
    public function rules(){
        return [
            'organize_id'                      => 'required',
            'isCooperation'                    => 'required',
            'isAgencyAgreement'                => 'required',
            'group_id'                         => 'nullable|integer',
            'participant'                      => 'required',
            'participant.*.tin'                => 'required',
            'participant.*.name'               => 'required',
            'participant.*.address'            => 'required',
            'participant.*.email'              => 'required|email',
            'participant.*.phone'              => 'required|numeric',
            'participant.*.date_of_submission' => 'required|date'
        ];
    }
}
