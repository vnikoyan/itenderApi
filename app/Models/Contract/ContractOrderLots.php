<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Model;

class ContractOrderLots extends Model
{
    protected $table = 'contract_order_lots';

    protected $allowed = ["ordered"];
    protected $default = ["ordered"];

    public function contractOrder(){
        return $this->hasOne('App\Models\Contract\ContractOrders', 'id', 'contract_order_id');
    }

    public function lot(){
        return $this->hasOne('App\Models\Contract\ContractLots', 'id', 'contract_lot_id')->with('organizeRow');
    }
}
