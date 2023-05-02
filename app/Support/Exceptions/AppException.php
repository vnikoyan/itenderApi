<?php

// Define the namespace
namespace App\Support\Exceptions;

// Include any required classes, interfaces etc...
use Illuminate\Support\Facades\Lang;


class AppException extends \Exception
{
	/**
	 * Type
	 *
	 * @var boolean
	 */
	private $type;

	/**
	 * The Error Information
	 *
	 * @var array
	 */
	private $info = NULL;

	/**
	 * Bubbla Exception.
	 *
	 * @param array  $type
	 * @param array  $errors
	 */
	public function __construct($type = NULL, $errors = NULL)
	{
		$this->type = $type;
		$this->info = $errors;
		parent::__construct($this->getCustomMessage(), $this->getHttpCode(), NULL);
	}

	/**
	 * Report or log an exception.
	 *
	 * @param \Exception $e
	 * @return void
	 */
	public function report(\Exception $e)
	{
		if (!$this->shouldReport()) {
			return;
		}

		parent::report($e);
	}

	/**
	 * Get HTTP Code
	 *
	 * @access public
	 * @return int
	 */
	public function getHttpCode() {
		return isset($this->type['http']) ? $this->type['http'] : 400;
	}

	/**
	 * Get Error Information
	 *
	 * @access public
	 * @return array
	 */
	public function getInfo() {
		return $this->info;
	}

	/**
	 * Get Custom Message
	 *
	 * @access public
	 * @return string
	 */
	public function getCustomMessage() {
		return Lang::get('exceptions.' . $this->type['code']);
	}

	/**
	 * Get Custom Code
	 *
	 * @access public
	 * @return int
	 */
	public function getCustomCode() {
		return isset($this->type['code']) ? $this->type['code'] : 0;
	}

	/**
	 * Determine whether exception should be reported
	 *
	 * @access public
	 * @return boolean
	 */
	public function shouldReport() {
		return false;
		return isset($this->type['report']) ? $this->type['report'] : false;
	}
}