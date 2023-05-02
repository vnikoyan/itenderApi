<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class CoWorkers extends Model
{
    use HasTranslations;

    protected $table = 'co_workers';
    
    public $translatable = ['title'];
    
    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }

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