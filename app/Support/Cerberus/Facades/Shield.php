<?php

// Define the namespace
namespace App\Support\Cerberus\Facades;

// Include any required classes, interfaces etc...
use Illuminate\Support\Facades\Facade;

/**
 * Shield
 *
 * @author      Ivan Ivanov <ivan@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 * @see        \App\Support\Cerberus\Cerberus
 */
class Shield extends Facade {

	protected static function getFacadeAccessor() { return 'shield'; }

}