<?php


namespace App\Models\PurchasingProcess;

use App\Models\AbstractModel;

class PurchasingProcessUser extends AbstractModel{

    protected $table = 'purchasing_process_user';

    protected $allowed = [ "purchasing_process_id","user_id"];
    protected $default = [ "purchasing_process_id","user_id"];



}
