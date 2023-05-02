<?php


namespace App\Http\Requests\Organize;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultiOrganizeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */

    public function rules()
    {
        return array(

            "organize"                            => "required",
            "organize.*.id"                        => "required",
            "organize.*.procurement_plan_id"       => "nullable|integer",
            "organize.*.organize_type"             => "nullable|in:0,1",
            "organize.*.text_approval_date"        => "nullable|date_format:Y-m-d",
            "organize.*.decision_number"           => "nullable|integer",
            "organize.*.name"                      => "nullable",
            "organize.*.code"                      => "nullable",
            "name.organize.*.hy"                   => "nullable",
            "code.organize.*.hy"                   => "nullable",
            "name.organize.*.ru"                   => "nullable",
            "code.organize.*.ru"                   => "nullable",

            "organize.*.public_date"               => "nullable|date|date_format:Y-m-d",
            "organize.*.submission_date"           => "nullable|integer",
            "organize.*.opening_date"              => "nullable|date|date_format:Y-m-d",
            "organize.*.opening_time"              => "nullable|date_format:H:i",

            "organize.*.prepayment"                => "nullable|integer|in:0,1",
            "organize.*.prepayment_max"            => "numeric|required_if:prepayment,==,1",
            "organize.*.prepayment_time"           => "date_format:Y-m|required_if:prepayment,==,1",


            "organize.*.paper_fee"                 => "nullable|integer|in:0,1",
            "organize.*.fee"                       => "numeric|required_if:paper_fee,==,1",
            "organize.*.account_number"            => "numeric|required_if:paper_fee,==,1",
//
//            "organize.*.count"                     => "nullable|numeric", //        Քանակ
//            "organize.*.supply"                    => "nullable|numeric", //        Մատակարարման ենթակա քանակը
//            "organize.*.supply_date"               => "nullable|integer", // //     Մատակարարման ժամկետը
//            "organize.*.is_main_tool"              => "nullable|integer|in:0,1", // Հիմնական միջոց հանդիսացող
//            "organize.*.is_collateral_requirement" => "nullable|integer|in:0,1", // Հայտի ապահովման ներկայացման պահանջը
//            "organize.*.is_product_info"           => "nullable|integer|in:0,1", //  Մասնակցի կողմից առաջարկվող ապրանքի, ապրանքային նշանի, ֆիրմային անվանման, մակնիշի և արտադրողի անվանման և ծագման երկրի վերաբերյալ տեղեկատվության ներկայացում

            "organize.*.evaluator"                 => "nullable",
            "organize.*.evaluator_president"       => "required_if:organize.*.evaluator,==,1",
            "organize.*.evaluator_secretary"       => "required_if:organize.*.evaluator,==,1",
            "organize.*.evaluator_secretary_name"  => "required_if:organize.*.evaluator,==,1",
            "organize.*.evaluator_secretary_email" => "email|required_if:organize.*.evaluator,==,1",
            "organize.*.evaluator_secretary_phone" => "email|required_if:organize.*.evaluator,==,1",
            "organize.*.evaluator_member"          => "required_if:organize.*.evaluator,==,1"
        );
    }
}
