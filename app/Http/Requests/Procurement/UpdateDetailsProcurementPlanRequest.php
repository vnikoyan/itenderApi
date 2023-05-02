<?php


namespace App\Http\Requests\Procurement;


use Illuminate\Foundation\Http\FormRequest;



class UpdateDetailsProcurementPlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
    */
    public function rules(){
        return [
            'count'                => 'nullable',
            'unit_amount'          => 'nullable',
            'type'                 => 'nullable|integer',
        ];
    }

}
