<?php

namespace App\Models\Cpv;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\AbstractModel;

class Specifications extends AbstractModel
{
    use HasTranslations;
    public $translatable = ['description'];



    protected $allowed = ["description", "users_id",'translations'];
    
    protected $default = ["description", "users_id",'translations'];

    
    protected $fillable = ["description", "users_id",'translations'];

    protected $table = 'specifications';

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

    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }

    public function statistics(){
        return $this->hasMany('App\Models\Statistics\CpvStatistics', 'specification_id');
    }

}
