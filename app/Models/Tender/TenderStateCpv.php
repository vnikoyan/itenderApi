<?php

namespace App\Models\Tender;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class TenderStateCpv extends Model{

    protected $table = 'tender_state_cpv';

    public function cpv(){
        return $this->hasOne('App\Models\Cpv\Cpv', 'id', 'cpv_id')->with('users')->with('specifications');
    }

    public function cpvData(){
        return $this->hasOne('App\Models\Cpv\Cpv', 'id', 'cpv_id');
    }

    public function tender(){
        return $this->hasOne('App\Models\Tender\TenderState', 'id', 'tender_state_id');
    }

    public function statistics(){
        return $this->hasOne('App\Models\Statistics\CpvStatistics', 'tender_state_cpv_id', 'id')->with('participants');
    }
    
} 