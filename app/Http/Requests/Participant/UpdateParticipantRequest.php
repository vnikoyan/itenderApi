<?php


namespace App\Http\Requests\Participant;


use Illuminate\Foundation\Http\FormRequest;

class UpdateParticipantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'tin'                => 'nullable',
            'name'               => 'nullable',
            'address'            => 'nullable',
            'email'              => 'nullable|email',
            'phone'              => 'nullable|numeric',
            'date_of_submission' => 'nullable|date'
        ];
    }
}
