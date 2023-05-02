<?php

namespace App\Models\Organize;

use Spatie\Translatable\HasTranslations;
use App\Models\AbstractModel;

class OrganizeRowExcel extends AbstractModel{

    use HasTranslations;

    protected $table = 'organize_row_excel';

    public $translatable = ['specification', 'cpv_name', 'unit'];

    protected $allowed = [
        'organize_id',
        'cpv_id',
        'cpv_code',
        'specification',
        'cpv_name',
        'unit',
        'total_price',
        'unit_amount',
    ];
    protected $default = [
        'organize_id',
        'cpv_id',
        'cpv_code',
        'specification',
        'cpv_name',
        'unit',
        'total_price',
        'unit_amount',
    ];

}
