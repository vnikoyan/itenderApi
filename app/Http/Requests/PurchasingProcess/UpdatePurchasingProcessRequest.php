<?php

namespace App\Http\Requests\PurchasingProcess;


use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchasingProcessRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'procurement_plan_id'              => 'nullable|integer',
            'organize_id'                      => 'nullable|integer',
            'count'                            => 'nullable|integer',
            'code'                             => 'nullable',
            'address'                          => 'nullable',
            'other_requirements'               => 'nullable',

            'is_full_decide'                   => 'nullable',
            'is_all_participants'              => 'nullable',
            'deadline'                         => 'nullable',
        ];
    }
}
