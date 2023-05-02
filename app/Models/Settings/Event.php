<?php

namespace App\Models\Settings;

use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Event extends AbstractModel{

    use HasTranslations;
    //

    protected $table = 'event';

    public $translatable = ['title','description'];

    protected $allowed = ["title","description","medias","image","youtube_link","translations","created_at"];

    protected $default = ["title","description","medias","image","youtube_link","translations","created_at"];

    public function getMedia(){
        return $this->hasOne('App\Models\Settings\EventMedia', 'event_id', 'id')->where("type",1);
    }

    public function medias(){
        return $this->hasMany('App\Models\Settings\EventMedia', 'event_id', 'id');
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
