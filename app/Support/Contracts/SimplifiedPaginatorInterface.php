<?php

// Define the namespace
namespace App\Support\Contracts;


interface SimplifiedPaginatorInterface
{
	/**
	 * Returns the total number of items.
	 *
	 * @return integer
	 */
	public function total();
	
	/**
	 * Returns the items.
	 *
	 * @return integer
	 */
	public function items();

	/**
	 * Determines whether the there are more pages to retrieve.
	 *
	 * @return boolean
	 */
	public function hasMorePages();
}