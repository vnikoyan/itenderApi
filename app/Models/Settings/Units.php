<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;
use App\Models\AbstractModel;

class Units extends AbstractModel
{
    use HasTranslations;

    protected $table = 'units';
    protected $allowed = ["title"];

    protected $default = ["title"];
    
    public $translatable = ['title'];

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