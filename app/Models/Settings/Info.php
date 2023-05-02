<?php

namespace App\Models\Settings;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class Info extends AbstractModel{

    use HasTranslations;

    protected $table = 'info';

    public $translatable = ['title','description'];

    protected $allowed = ["title","description"];

    protected $default = ["title","description"];

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
