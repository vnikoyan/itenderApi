<?php

namespace App\Models\User;

use Spatie\Translatable\HasTranslations;
use Illuminate\Notifications\Notifiable;
use App\Models\AbstractModel;

class Members extends AbstractModel
{
    use HasTranslations,Notifiable;

    protected $table = 'members';

    public $translatable = ['name',"position"];

    public $allowed      = ['translations',"name","position"];

    public $default      = ['translations',"name","position"];

}
