<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\AbstractModel;

class Classifier extends AbstractModel
{
    protected $table = 'classifier';


    protected $allowed = ["title","code"];

    protected $default = ["title","code"];


    protected $fillable = ['name'];

    protected $visible = ['title', 'code'];


    public function cpv()
    {
        return $this->hasMany('App\Models\Settings\ClassifierCpv', 'classifier_id', 'id');
    }
}
