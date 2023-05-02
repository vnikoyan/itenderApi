<?php

// Define the namespace
namespace App\Support\Cerberus;

// Include any required classes, interfaces etc...
use App\Support\Cerberus\Contracts\CallerInterface;
use App\Support\Cerberus\Contracts\AuthorizationDriver;


class Cerberus
{
	/**
	 * The API caller instance.
	 *
	 * @var CallerInterface
	 */
	protected $caller;

	/**
	 * Authorization driver instance.
	 *
	 * @var AuthorizationDriver
	 */
	protected $driver;
	
	/**
	 * Cerberus Class Constructor.
	 *
	 * @param AuthorizationDriver $driver
	 */
	public function __construct(AuthorizationDriver $driver)
	{
		$this->driver = $driver;
	}
	
	/**
	 * Check the incoming request for valid authorization credentials.
	 *
	 * @return bool
	 */
	public function validate()
	{
		if ($this->caller) {
			return true;
		}

		$this->caller = $this->driver->authorize();

		return (bool) $this->caller;
	}
	
	/**
	 * Returns the caller's associated user id.
	 *
	 * @return int|mixed
	 */
	public function id()
	{
		return isset($this->caller) ? $this->caller->id() : null;
	}

	/**
	 * Returns the caller's associated user account.
	 *
	 * @return mixed|null
	 */
	public function user()
	{
		return $this->caller ? $this->caller->user() : null;
	}

	/**
	 * Returns the authenticated caller.
	 *
	 * @return CallerInterface
	 */
	public function caller()
	{
		return $this->caller;
	}

	/**
	 * Returns the unique ID of the authenticated caller.
	 *
	 * @return int|null
	 */
	public function callerId()
	{
		return $this->caller->id();
	}

	/**
	 * Return the version of the software agent.
	 *
	 * @return string
	 */
	public function version()
	{
		return $this->driver->version();
	}
}