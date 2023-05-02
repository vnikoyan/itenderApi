<?php

namespace App\Models\Package;

use App\Models\AbstractModel;
use Spatie\Permission\Traits\HasRoles;

class Package extends AbstractModel
{
    protected $table = 'packages';

    use HasRoles;
    Protected $guard_name ='web';

    protected $allowed = ["name","price"];

    protected $default = ["name","price"];



}
