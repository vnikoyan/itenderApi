<?php

namespace App\Models\Tender;

use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    protected $table = 'user_application';

    public function tender(){
        return $this->hasOne('App\Models\Tender\TenderState', 'id', 'tender_id');
    }
}
