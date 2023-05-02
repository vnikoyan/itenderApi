<?php

namespace App\Imports;

use App\Models\Cpv\CpvOutside;
use App\Models\Cpv\Specifications;
use App\Models\Organize\OrganizeRow;
use App\Models\Procurement\ProcurementPlan;
use App\Models\Procurement\ProcurementPlanDetails;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;

class OrganizeRowsImport implements ToArray
{
    /**
    * @param Collection $collection
    */
    public function array(array $rows)
    {
        return array($rows[0], $rows[4]);
    }
}
