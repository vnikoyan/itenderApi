<?php


namespace App\Http\Requests\User;

use App\Http\Requests\AbstractRequest;

class ForgotUserPasswordRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'      => 'required|email',
        ];
    }
}