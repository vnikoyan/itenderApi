<?php


namespace App\Models\Settings;

use App\Models\AbstractModel;


class FinancialClassifier extends AbstractModel
{
    protected $table = 'financial_classifier';


    protected $allowed = ["title","code"];

    protected $default = ["title","code"];


    protected $fillable = ['name'];

    protected $visible = ['title', 'code'];


}
