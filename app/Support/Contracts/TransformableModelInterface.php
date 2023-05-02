<?php

// Define the namespace
namespace App\Support\Contracts;


interface TransformableModelInterface
{
	/**
	 * Filters the requested fields and returns a list of the allowed ones.
	 *
	 * @param array $fields
	 * @return array
	 */
	public function getAccessibleFields(array $fields = []);
}