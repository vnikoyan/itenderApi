<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model{

    protected $table = 'payment_history';

        
    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }


}