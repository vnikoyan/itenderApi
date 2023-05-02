<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderState extends Model{

    protected $table = 'order_state';

            
    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }    

    public function packageUser(){
        return $this->hasOne('App\Models\User\Organisation',"id","organisation_id");
    } 
    public function packageState(){
        return $this->hasOne('App\Models\Package\PackageState', 'id', 'package_id');
    }
        
    public function packageOnePerson(){
        return $this->hasOne('App\Models\Package\PackageState', 'id', 'package_id_one_person');
    }
        
    public function packageCompetitive(){
        return $this->hasOne('App\Models\Package\PackageState', 'id', 'package_id_competitive');
    }


}