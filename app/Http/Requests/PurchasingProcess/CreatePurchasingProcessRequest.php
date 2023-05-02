<?php


namespace App\Http\Requests\PurchasingProcess;


use Illuminate\Foundation\Http\FormRequest;

class CreatePurchasingProcessRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * UpdateParticipantRequest CreateSelectedParticipantRequest
     * @return array
     */
    public function rules(){
        return [
           'procurement_plan_id'              => 'required|integer',
//           'organize_id'                      => 'required|integer',
           'count'                            => 'required|integer',
           'code'                             => 'required',
           'address'                          => 'required',
           'other_requirements'               => 'required',

           'is_full_decide'                   => 'required|in:0,1',
           'is_all_participants'              => 'required|integer|in:0,1',
           'deadline'                         => 'required|date_format:Y-m-d',
           'users'                             => 'required_if:is_all_participants,==,0',
           'users.*.user_id'                   => 'required|integer',
        ];
    }
}
