<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class Guide extends Model{

    use HasTranslations;

    protected $table = 'guides';
    
    public $translatable = ['title','description','youtube_link','file'];
    
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