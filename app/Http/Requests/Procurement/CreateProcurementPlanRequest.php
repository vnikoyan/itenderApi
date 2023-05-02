<?php
namespace App\Http\Requests\Procurement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class CreateProcurementPlanRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            'procurement_id'                     => 'required',
            'procurement'                        => 'required',
            'procurement.*.cpv_id'               => 'required',
            'procurement.*.cpv_type'             => 'required|in:1,2,3',

//            'procurement.*.cpv_drop'             => 'required|integer',
            'procurement.*.user_id'              => 'required|integer',

            'procurement.*.user_id_1'            => 'nullable|integer',
            'procurement.*.user_id_2'            => 'nullable|integer',
            'procurement.*.user_id_3'            => 'nullable|integer',
            'procurement.*.user_id_4'            => 'nullable|integer',
            'procurement.*.user_id_5'            => 'nullable|integer',

            'procurement.*.is_condition'         => 'nullable|integer',

            'procurement.*.status'               => 'nullable|integer',
            'procurement.*.specifications_id'    => 'nullable|integer',



            'procurement.*.plan_details'                        => 'required',
            'procurement.*.plan_details.*.count'                => 'required|numeric',
            'procurement.*.plan_details.*.unit_amount'          => 'required|numeric',
            'procurement.*.plan_details.*.type'                 => 'required|integer',
            'procurement.*.plan_details.*.classifier_id'        => 'nullable|integer',
            'procurement.*.plan_details.*.out_count'            => 'nullable|numeric',
            'procurement.*.plan_details.*.date'                 => 'nullable|date',

        ];
    }

}
