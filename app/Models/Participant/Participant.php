<?php

namespace App\Models\Participant;

use App\Models\AbstractModel;
use Spatie\Translatable\HasTranslations;

class Participant extends AbstractModel{
    use HasTranslations;

    protected $table = 'participant_data';

    public $translatable = ['address',"name","first_name","last_name","middle_name"];

    protected $allowed = [ "group_id","organize_id","name","address","tin","email","phone","date_of_submission"];
    protected $default = [ "group_id","organize_id","name","address","tin","email","phone","date_of_submission"];

}
