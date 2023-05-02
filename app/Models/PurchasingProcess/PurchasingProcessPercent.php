<?php


namespace App\Models\PurchasingProcess;

use App\Models\AbstractModel;

class PurchasingProcessPercent extends AbstractModel{

    protected $table = 'purchasing_process_percent';



    protected $allowed = [
        'name',
        'purchasing_process_id',
        'month_1',
        'month_2',
        'month_3',
        'month_4',
        'month_5',
        'month_6',
        'month_7',
        'month_8',
        'month_9',
        'month_10',
        'month_11',
        'month_12'
    ];
    protected $default = [
        'name',
        'purchasing_process_id',
        'month_1',
        'month_2',
        'month_3',
        'month_4',
        'month_5',
        'month_6',
        'month_7',
        'month_8',
        'month_9',
        'month_10',
        'month_11',
        'month_12'
    ];



}
