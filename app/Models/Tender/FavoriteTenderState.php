<?php

namespace App\Models\Tender;

use Illuminate\Database\Eloquent\Model;

class FavoriteTenderState extends Model
{
    protected $table = 'favorite_tender_states';

    public function tender(){
        return $this->hasOne('App\Models\Tender\TenderState', 'id', 'tender_state_id');
    }

}
