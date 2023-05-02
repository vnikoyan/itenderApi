<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\AbstractModel;

class ClassifierCpv extends AbstractModel
{
    protected $table = 'classifier_cpv';



    protected $visible = ['id', 'classifier',"cpv"];

    public function cpv()
    {
        return $this->hasOne('App\Models\Cpv\Cpv', 'id', 'cpv_id');

    }

    public function classifier()
    {
        return $this->hasOne('App\Models\Settings\Classifier', 'id', 'classifier_id');

    }

}
