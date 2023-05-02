<?php
namespace App\Http\Requests\Procurement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class UpdateProcurementPlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            'cpv_id'               => 'nullable',
            'unit'                 => 'nullable',
            'count'                => 'nullable',
            'unit_amount'          => 'nullable',
            'type'                 => 'nullable|integer',
//            'cpv_drop'             => 'nullable|integer',
            'user_id'              => 'required|integer',

            'user_id_1'            => 'nullable|integer',
            'user_id_2'            => 'nullable|integer',
            'user_id_3'            => 'nullable|integer',
            'user_id_4'            => 'nullable|integer',
            'user_id_5'            => 'nullable|integer',

            'is_condition'         => 'nullable|integer',
            'date'                 => 'nullable|date',

            'status'               => 'nullable|integer',
            'specifications_id'    => 'nullable|integer',
            'classifier_id'        => 'nullable|integer',
        ];
    }

}
