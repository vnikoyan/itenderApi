<?php

// Define the namespace
namespace App\Support\Cerberus;

// Include any required classes, interfaces etc...
use JWTAuth;
use Illuminate\Http\Request;
use App\Support\Cerberus\Contracts\AuthProvider;
use App\Support\Cerberus\Contracts\AuthorizationDriver;

class JWTShield implements AuthorizationDriver
{
	/**
	 * Current request.
	 *
	 * @var \Illuminate\Http\Request;
	 */
	protected $request;

	/**
	 * Provider for authorized entity.
	 *
	 * @var AuthProvider
	 */
	protected $provider;

	/**
	 * JWTShield Class Constructor.
	 *
	 * @param Request $request
	 * @param AuthProvider $provider
	 */
	public function __construct(Request $request, AuthProvider $provider)
	{
		$this->request = $request;
		$this->provider = $provider;
	}

	/**
	 * Return the version of the software agent.
	 *
	 * @return string
	 */
	public function version()
	{
		return $this->request->header('User-Agent');
	}

	/**
	 * Return the authorized user.
	 *
	 * @return object
	 */
	public function authorize()
	{
		$jwt = $this->getAuthorizationForRequest();

		if(!$jwt){
			return false;
		}
		
		$user = JWTAuth::toUser($jwt);

		if (!isset($user->id)) {
			return false;
		}

		$caller = $this->provider->retrieveById($user->id);

		return $caller;
	}

	/**
	 * Return the access token.
	 *
	 * @return string
	 */
	protected function getAuthorizationForRequest()
	{
		$token = $this->request->bearerToken();

		if (!$token) {
			$token = $this->request->query('access_token');
		}

		return $token;
	}
}