<?php

namespace App\Models\Package;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class PackageState extends Model
{
    protected $table = 'packages_state';

    use HasRoles;
    Protected $guard_name ='web';
    
}
