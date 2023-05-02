<?php
namespace App\Models\Organize;

use App\Models\AbstractModel;

class OrganizeRowPercent extends AbstractModel
{
    protected $table = 'organize_row_percent';

    protected $allowed = [
        'name',
        'organize_row_id',
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
        'organize_row_id',
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

    public function organizeRow()
    {
        return $this->hasOne('App\Models\Organize\OrganizeRow', 'id', 'organize_row_id');
    }

}
