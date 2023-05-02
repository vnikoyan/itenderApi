<?php


namespace App\Http\Requests\PurchasingProcess;


use Illuminate\Foundation\Http\FormRequest;

class CreatePurchasingProcessUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * UpdateParticipantRequest CreateSelectedParticipantRequest
     * @return array
     */
    public function rules(){
        return [
            'users'              => 'required',
            'users.*.user_id'    => 'required|integer',
        ];
    }
}
