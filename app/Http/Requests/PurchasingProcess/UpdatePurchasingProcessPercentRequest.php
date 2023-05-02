<?php


namespace App\Http\Requests\PurchasingProcess;


use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchasingProcessPercentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * UpdateParticipantRequest CreateSelectedParticipantRequest
     * @return array
     */
    public function rules(){
        return [ //UpdateOrganizeRowRequest
            "name"                      => "required",
        ];
    }
}
