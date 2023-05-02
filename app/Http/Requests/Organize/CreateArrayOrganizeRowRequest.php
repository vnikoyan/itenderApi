<?php


namespace App\Http\Requests\Organize;

use Illuminate\Foundation\Http\FormRequest;

class CreateArrayOrganizeRowRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [ //UpdateOrganizeRowRequest
            "organize_row"                             => "required",
            "organize_row.*.procurement_plan_id"       => "required|integer",
            "organize_row.*.plan_details_id"           => "required|integer",
            "organize_row.*.organize_id"               => "required|integer",
            "organize_row.*.count"                     => "required|numeric", //        Քանակ
        ];
    }
}
