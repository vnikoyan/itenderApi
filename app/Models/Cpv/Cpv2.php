<?php

namespace App\Models\Cpv;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\AbstractModel;

class Cpv2 extends AbstractModel
{
    use Notifiable;
    protected $fillable = ['title',"code", "name", "unit", "type","parent_id"];

    protected $table = 'cpv2';

    public function categoryParent(){
        return $this->belongsTo('App\Models\Cpv\Cpv2', 'parent_id');
    }
    
    public function children(){
        return $this->hasMany('App\Models\Cpv\Cpv2', 'parent_id')->with('children');
    }

    
}
