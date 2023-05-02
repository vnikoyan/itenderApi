<?php

namespace App\Models\Tender;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class ParserState extends Model{

    use HasTranslations;
    //

    protected $table = 'tender_state_parser';
    
    
    /**
     * Encode the given value as JSON.
     *
     * @param  mixed  $value
     * @return string
     */
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
    
}