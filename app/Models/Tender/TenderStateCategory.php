<?php

namespace App\Models\Tender;

use Illuminate\Database\Eloquent\Model;

class TenderStateCategory extends Model
{
    protected $table = 'tender_state_category';

    public $timestamps = false;

    public function category(){
        return $this->hasOne('App\Models\Categories\Categories', 'id', 'category_id');
    }
}
