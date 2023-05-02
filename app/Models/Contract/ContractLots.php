<?php

namespace App\Models\Contract;

use App\Models\AbstractModel;

class ContractLots extends AbstractModel
{
    protected $table = 'contract_lots';

    protected $allowed = ["total_price", "ordered", "supplied", "available", "price_unit"];
    protected $default = ["total_price", "ordered", "supplied", "available", "price_unit"];

    public function hasFailedOrder(){
        foreach ($this->lots as $lot) {
            if($lot->contractOrder->status === 'canceled'){
                return true;
            }
        }
        return false;
    }

    public function activeOrdersCount(){
        $count = 0;
        foreach ($this->lots as $lot) {
            if($lot->contractOrder->status === 'sended'){
                $count++;
            }
        }
        return $count;
    }

    public function deliveryDate(){
        $organize = $this->organizeRow->organize || $this->organizeRow->organizeOnePerson || $this->organizeRow->organizeItender;
        return 'Պատվերը ստանալուց՝ '.($organize).' աշխատանքային օրվա ընթացքում';
    }

    public function contract(){
        return $this->hasOne('App\Models\Contract\Contracts', 'id', 'contract_id');
    }

    public function lots(){
        return $this->hasMany('App\Models\Contract\ContractOrderLots', 'contract_lot_id', 'id');
    }
    
    public function organizeRow(){
        return $this->hasOne('App\Models\Organize\OrganizeRow', 'id', 'organize_row_id')->with('organize')->with('organizeOnePerson');
    }
}
