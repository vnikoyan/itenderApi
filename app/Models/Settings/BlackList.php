<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class BlackList extends Model
{
    protected $table = 'black_list';
    
    protected $fillable = ['name'];

}
