<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Notifications\Notifiable;
class Order extends Model{

    protected $table = 'order';

    use HasTranslations,Notifiable;


    public $translatable = ['name',"company_type","nickname","region","city","address","bank_name","director_name"];

    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id')->with('organisation');
    }

        
    public function package(){
        return $this->hasOne('App\Models\Package\Package', 'id', 'package_id');
    }

}