<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CpvList implements ToCollection
{
    protected $encoding = 'UTF-8';

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }
}
