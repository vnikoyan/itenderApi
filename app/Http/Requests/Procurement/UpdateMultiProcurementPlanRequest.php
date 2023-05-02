<?php
namespace App\Http\Requests\Procurement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class UpdateMultiProcurementPlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){ 
        return [
            'procurement'                        => 'required',
            'procurement.*.id'                   => 'required|integer',
            'procurement.*.cpv_id'               => 'nullable',
            'procurement.*.unit'                 => 'nullable',
            'procurement.*.count'                => 'nullable',
            'procurement.*.unit_amount'          => 'nullable',
            'procurement.*.type'                 => 'nullable|integer',
            'procurement.*.cpv_drop'             => 'nullable|integer',
            'procurement.*.user_id'              => 'required|integer',

            'procurement.*.user_id_1'            => 'nullable|integer',
            'procurement.*.user_id_2'            => 'nullable|integer',
            'procurement.*.user_id_3'            => 'nullable|integer',
            'procurement.*.user_id_4'            => 'nullable|integer',
            'procurement.*.user_id_5'            => 'nullable|integer',
            
            'procurement.*.is_condition'         => 'nullable|integer',
            'procurement.*.date'                 => 'nullable|date',

            'procurement.*.status'               => 'nullable|integer',
            'procurement.*.specifications_id'    => 'nullable|integer',
            'procurement.*.classifier_id'        => 'nullable|integer',
        ];  
    }

}