<?php

// Define the namespace
namespace App\Support\Cerberus\Contracts;


interface CallerInterface
{
	/**
	 * Returns the caller id.

	 * @return int|null
	 */
	public function id();
	
	/**
	 * Returns the caller's associated user account.
	 *
	 * @return mixed
	 */
	public function user();
}