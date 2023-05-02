<?php


namespace App\Http\Requests\Organize;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */

    public function rules()
    {
        return array(

            "procurement_plan_id"       => "nullable|integer",
            "organize_type"             => "nullable|in:0,1",
            "text_approval_date"        => "nullable|date_format:Y-m-d",
            "decision_number"           => "nullable|integer",
            "name"                      => "nullable",
            "code"                      => "nullable",
            "name.hy"                   => "nullable",
            "code.hy"                   => "nullable",
            "name.ru"                   => "nullable",
            "code.ru"                   => "nullable",

            "public_date"               => "nullable|date|date_format:Y-m-d",
            "submission_date"           => "nullable|integer",
            "opening_date"              => "nullable|date|date_format:Y-m-d",
            "opening_time"              => "nullable|date_format:H:i",

            "prepayment"                => "nullable|integer|in:0,1",
            "prepayment_max"            => "numeric|required_if:prepayment,==,1",
            "prepayment_time"           => "date_format:Y-m|required_if:prepayment,==,1",


            "paper_fee"                 => "nullable|integer|in:0,1",
            "fee"                       => "numeric|required_if:paper_fee,==,1",
            "account_number"            => "numeric|required_if:paper_fee,==,1",


//            "count"                     => "nullable|numeric", //        Քանակ
//            "supply"                    => "nullable|numeric", //        Մատակարարման ենթակա քանակը
//            "supply_date"               => "nullable|integer", // //     Մատակարարման ժամկետը
//            "is_main_tool"              => "nullable|integer|in:0,1", // Հիմնական միջոց հանդիսացող
//            "is_collateral_requirement" => "nullable|integer|in:0,1", // Հայտի ապահովման ներկայացման պահանջը
//            "is_product_info"           => "nullable|integer|in:0,1", //  Մասնակցի կողմից առաջարկվող ապրանքի, ապրանքային նշանի, ֆիրմային անվանման, մակնիշի և արտադրողի անվանման և ծագման երկրի վերաբերյալ տեղեկատվության ներկայացում

            "evaluator"                 => "nullable", //  Եթե առաջարկվող գները ներկայացված են երկու կամ ավելի արժեքներով, ապա դրանք համեմատվում են ՀՀ դրամով
            "evaluator_president"       => "required_if:evaluator,==,1",
            "evaluator_secretary"       => "required_if:evaluator,==,1",
            "evaluator_secretary_name"  => "required_if:evaluator,==,1",
            "evaluator_secretary_email" => "email|required_if:evaluator,==,1",
            "evaluator_secretary_phone" => "required_if:evaluator,==,1",
            "evaluator_member"          => "required_if:evaluator,==,1"
        );
    }
}
