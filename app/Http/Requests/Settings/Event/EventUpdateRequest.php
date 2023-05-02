<?php
namespace App\Http\Requests\Settings\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Translation\Language;


class EventUpdateRequest extends FormRequest 
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
    */
    public function rules(){
        $rules =  [
            'order'          => 'required|integer',
            'image.*'        => 'mimes:jpeg,jpg,png,gif|max:20000',
            'video'          => 'mimes:mp4,mov,ogg,qt,webm|max:30000',
            "title"          => "required|array",
            "description"    => "required|array",
        ];
        foreach (Language::getLanguages(Language::STATUS_ACTIVE) as $value){
            $rules["title.".$value->code] = 'required';
            $rules["description.".$value->code] = 'required';
        }
        return $rules;
    }

}