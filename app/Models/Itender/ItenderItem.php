<?php

namespace App\Models\Itender;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class ItenderItem extends Model
{
    protected $table = 'itender_item';
    
    protected $fillable = ['name'];

    public function itender(){
        return $this->hasOne('App\Models\Itender\Itender', 'id', 'itender_id');
    }
    // php artisan make:migration create_ItenderItem_table

}
