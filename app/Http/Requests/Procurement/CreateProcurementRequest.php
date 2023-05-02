<?php
namespace App\Http\Requests\Procurement;

use Illuminate\Foundation\Http\FormRequest;


class CreateProcurementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            // 'year'         => 'required|digits:4|integer|min:2010|unique:procurements,year,'.auth('api')->user()->parent_id.'year,organisation_id',
            'year'         => 'required|digits:4|integer|min:2010',

            // unique:mileages,date,NULL,id,user_id

            // 'count'                => 'required',
            // 'total_amount'         => 'required',
            // 'unit_amount'          => 'required',
            // 'purchasing_power'     => 'required',

            // 'financial_classifier' => 'required', // ֆինանսական դասակարգիչ
            // 'date' => 'required|date', // date
            // 'responsible_unit' => 'required', //  Պատասխանատու ստորաբաժանում
            // 'is_condition' => '', //  Պատասխանատու ստորաբաժանում
            // 'status' => '', //
        ];
    }

}
