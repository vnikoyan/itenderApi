<?php

// Define the namespace
namespace App\Support\Cerberus\Contracts;


interface AuthProvider
{
	/**
	 * Provides a valid authenticatable entity by given unique ID.
	 *
	 * @param $id
	 * @return CallerInterface|null
	 */
	public function retrieveById($id);
}