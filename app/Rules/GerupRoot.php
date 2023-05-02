<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use App\Models\User\User;

class GerupRoot implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $og_id; 
    public function __construct($og_id)
    {
        $this->og_id = $og_id;
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
         $root =  User::getGerupRootUserByGerupId($this->og_id);
         if($root->divisions == $value){
            return false;
         }
         return true;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'root';
    }
}