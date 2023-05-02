<?php


namespace App\Http\Requests\Organize;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrganizeRowPercentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [ //UpdateOrganizeRowRequest
            "name"                      => "required",
            "organize_row_id"           => "required|integer",
        ];
    }
}
