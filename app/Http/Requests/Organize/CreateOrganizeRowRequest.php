<?php


namespace App\Http\Requests\Organize;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrganizeRowRequest extends FormRequest
{
    /** CreateArrayOrganizeRowRequest
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [ //UpdateOrganizeRowRequest
            "procurement_plan_id"       => "nullable|integer",
             "plan_details_id"          => "nullable|integer",
            "organize_id"               => "nullable|integer",
            "shipping_address"          => "nullable", // shipping_address
            "count"                     => "nullable|numeric", //        Քանակ
            "supply"                    => "nullable|numeric", //        Մատակարարման ենթակա քանակը
            "supply_date"               => "nullable|integer", // //     Մատակարարման ժամկետը
            "is_main_tool"              => "nullable|integer|in:0,1", // Հիմնական միջոց հանդիսացող
            "is_collateral_requirement" => "nullable|integer|in:0,1", // Հայտի ապահովման ներկայացման պահանջը
            "is_product_info"           => "nullable|integer|in:0,1", //  Մասնակցի կողմից առաջարկվող ապրանքի, ապրանքային նշանի, ֆիրմային անվանման, մակնիշի և արտադրողի անվանման և ծագման երկրի վերաբերյալ տեղեկատվության ներկայացում

            'percent'                   => 'nullable',
            'percent.*.name'            => 'nullable',
            'percent.*.organize_row_id' => 'nullable|integer'
        ];
    }
}
