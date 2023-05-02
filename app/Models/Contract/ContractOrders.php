<?php

namespace App\Models\Contract;

use App\Models\AbstractModel;

class ContractOrders extends AbstractModel
{
    protected $table = 'contract_orders';

    protected $allowed = ["dispatch_date", "status"];
    protected $default = ["dispatch_date", "status"];

    public function contract(){
        return $this->hasOne('App\Models\Contract\Contracts', 'id', 'contract_id');
    }

    public function lots(){
        return $this->hasMany('App\Models\Contract\ContractOrderLots', 'contract_order_id', 'id');
    }

    public function index(){
        $found_key = array_search($this->id, array_column($this->contract->orders->toArray(), 'id'));
        return $found_key + 1;
    }
    
}
