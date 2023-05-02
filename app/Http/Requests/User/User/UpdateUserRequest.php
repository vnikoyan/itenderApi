<?php

// Define the namespace
namespace App\Http\Requests\User\User;

// Include any required classes, interfaces etc...
use App\Http\Requests\AbstractRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;

class UpdateUserRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        // var_dump(\Auth::guards()->user());

        $rules =  [
            'username'         => 'nullable',
        ];
  
        return $rules;
    }
}