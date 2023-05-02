<?php

namespace App\Models\Itender;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Itender extends Model
{
    protected $table = 'itender';
    
    protected $fillable = ['name'];

    public function getCpv(){
        return $this->hasOne('App\Models\Cpv\Cpv', 'id', 'cpv');
    }
    
    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }
    
    public function items(){
        return $this->hasMany('App\Models\Itender\ItenderItem', 'itender_id', 'id')->where('type', 1);
    }
    

}
