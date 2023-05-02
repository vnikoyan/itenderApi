<?php

// Define the namespace
namespace App\Support\Cerberus\Contracts;


interface AuthorizationDriver
{
	/**
	 * Check the incoming request for valid authorization credentials.
	 *
	 * @return CallerInterface|null
	 */
	public function authorize();
	
	/**
	 * Return the version of the software agent.
	 *
	 * @return string
	 */
	public function version();
}