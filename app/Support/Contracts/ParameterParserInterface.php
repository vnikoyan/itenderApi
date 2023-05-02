<?php

// Define the namespace
namespace App\Support\Contracts;


interface ParameterParserInterface
{
	/**
	 * Filters the requested fields and returns a list of the allowed ones.
	 *
	 * @return array
	 */
	public function getAccessibleFields();
	
	/**
	 * Returns a list of fields for a SELECT query.
	 * The list contains only the required for the response fields.
	 *
	 * @return array
	 */
	public function getSelectionFields();
}