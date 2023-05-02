<?php

// Define the namespace
namespace App\Support\Payments;

/**
 * Payment Driver Interface
 *
 * @author      Ivan Ivanov <ivan@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
interface PaymentDriver
{
	/**
	 * Determine if supplied receipt is valid.
	 *
	 * @return boolean
	 */
    public function isValidReceipt() : bool;

	/**
	 * Get the product id from the transaction.
	 *
	 * @return string
	 */
    public function getProductIdentifier() : string;

	/**
	 * Get the transaction id from the transaction.
	 *
	 * @param   boolean $original
	 * @return  string
	 */
    public function getTransactionUid($original = false) : string;

	/**
	 * Get the provider slug.
	 *
	 * @return string
	 */
	public function getProviderSlug() : string;

	/**
	 * Get the raw receipt string.
	 *
	 * @return string
	 */
	public function getRawReceipt() : string;
}