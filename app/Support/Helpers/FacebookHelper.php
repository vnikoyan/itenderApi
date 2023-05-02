<?php

// Define the namespace
namespace App\Support\Helpers;

// Include any required classes, interfaces etc...
use Facebook\Facebook;
use Facebook\GraphNodes;


class FacebookHelper
{
	/**
	 * Facebook Client.
	 * 
	 * @var Facebook
	 */
	protected $client;

	/**
	 * Access token for an app user.
	 * 
	 * @var string
	 */
	protected $token;
	
	/**
	 * Facebook Helper Class Constructor.
	 *
	 * @param array $config
	 * @param string $token
	 */
	public function __construct($config, $token = null)
	{
		$this->client = new Facebook($config);
		$this->token = $token;
	}

	/**
	 * Get User.
	 *
	 * @param array $fields
	 * @return object
	 */
	public function getUser($fields = [])
	{
		return (object) $this->request('/me?fields=' . implode(',' , $fields));
	}

	/**
	 * Request.
	 *
	 * @param array $query
	 * @return object
	 */
	private function request($query)
	{
		return $this->client
			->get($query, $this->token)
			->getDecodedBody();
	}
}