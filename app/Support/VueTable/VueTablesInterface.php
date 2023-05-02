<?php


namespace App\Support\VueTable;

/**
 *  VueTables server-side component interface
 */

Interface VueTablesInterface {

    public function get($table, Array $fields,Array $relationsFiter);

}
