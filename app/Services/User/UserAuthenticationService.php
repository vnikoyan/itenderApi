<?php

// Define the namespace
namespace App\Services\User;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use App\Models\User\User;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;

/**
 * User Authentication Service
 *
 * @author      Ben Carey <ben@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
class UserAuthenticationService
{
	/**
	 * Incoming HTTP Request.
	 *
	 * @var \Illuminate\Http\Request;
	 */
	protected $request;

	/**
	 * User Authentication Service Class Constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Connect a user with Facebook.
	 *
	 * @throws AppException
	 * @return User
	 */
	public function facebookConnect()
	{
		// $helper = new FacebookHelper(config('services.facebook'), $this->request->input('token'));
		// $facebook_user = $helper->getUser(['id','first_name','last_name','email','picture.width(720){url}']);

		// if (!isset($facebook_user->email)) {
		// 	throw new AppException(AppExceptionType::$NO_FACEBOOK_EMAIL);
		// }

		// $this->request['first_name']        = $facebook_user->first_name;
		// $this->request['last_name']         = $facebook_user->last_name;
		// $this->request['email']             = $facebook_user->email;
		// $this->request['facebook_id']       = $facebook_user->id;

		// $user = User::query()
		// 	->where('email', '=', $facebook_user->email)
		// 	->orWhere('facebook_id', '=', $facebook_user->id)
		// 	->first();

		// if(!$user) {
		// 	$user = User::build($this->request->all());
		// }else{
		// 	$user->edit($this->request->all());
		// }

		// if (isset($facebook_user->picture['data']['url'])) {
		// 	$manager = new FileManager('images');
		// 	$images = $manager->uploadImage($facebook_user->picture['data']['url'], ['users', $user->id], config('images.user_profiles'), false);

		// 	$this->request['profile_image'] = json_encode($images);
			
		// 	$user->edit($this->request->only('profile_image'));
		// }

		// return $user;
	}
	
	/**
	 * Creates a new user.
	 *
	 * @return User
	 */
	public function createUser()
	{
		$user = User::build($this->request->except('profile_image'));

		// if (!empty($this->request->input('profile_image'))) {
		// 	$manager = new FileManager('images');
		// 	$images = $manager->uploadImage($this->request->input('profile_image'), ['users', $user->id], config('images.user_profiles'), false);
		// 	$this->request['profile_image'] = json_encode($images);

		// 	$user->edit($this->request->only('profile_image'));
		// }
		
		return $user;
	}
	/**
	 * Creates a new user.
	 *
	 * @return User
	 */
	public function createUserByTin()
	{
		$user = User::build($this->request->except('profile_image'));

		// if (!empty($this->request->input('profile_image'))) {
		// 	$manager = new FileManager('images');
		// 	$images = $manager->uploadImage($this->request->input('profile_image'), ['users', $user->id], config('images.user_profiles'), false);
		// 	$this->request['profile_image'] = json_encode($images);

		// 	$user->edit($this->request->only('profile_image'));
		// }

		return $user;
	}

	/**
	 * Checks for valid login credentials.
	 *
	 * @throws AppException
	 * @return User
	 */
	public function signInUser()
	{

		$user = User::query()
			->where('username', '=', $this->request->input('username'))
			->first();

		if($user){
			if ($user->facebook_id) {
				throw new AppException(AppExceptionType::$FACEBOOK_ACCOUNT);
			}
	
			if (!$user->isCorrectPassword($this->request->input('password'))) {
				if(!$user->isCorrectOldPassword($this->request->input('password'))){
					throw new AppException(AppExceptionType::$WRONG_CREDENTIALS);
				}
			}
	
			if (!$user->is_confirmed) {
				throw new AppException(AppExceptionType::$NOT_CONFIRMED);
			}
	
			if ($user->status === 'BLOCK') {
				throw new AppException(AppExceptionType::$BLOCKED);
			}
	
			return $user;
		} else {
			throw new AppException(AppExceptionType::$WRONG_CREDENTIALS);
		}
	}

	/**
	 * Send the user an email to reset their password.
	 *
	 * @throws AppException
	 * @return boolean
	 */
	public function forgotPassword()
	{
	
		$user = User::query()
			->where('email', '=', $this->request->input('email'))
			->first();

		if ($user && $user->facebook_id) {
			return ["status"=>false,'text'=>"This profile register on Facebook!"];
			throw new AppException(AppExceptionType::$FACEBOOK_ACCOUNT);
		}
		if (!$user) {
			return ["status"=>false,'text'=>"No results found for your request!"];
			throw new AppException(AppExceptionType::$EMAIL_NOT_FOUND);
		}

		$status = $user->passwordReset();

		return ["status"=>true];

		return $status;
	}

	/**
	 * Reset the users password.
	 *
	 * @throws AppException
	 * @return boolean
	 */
	public function resetPassword()
	{
		$user = User::where('forgot_token',$this->request->input('token'))
			->first();
		
		if (!$user) {
			throw new AppException(AppExceptionType::$INVALID_TOKEN);
		}

		if (strtotime($user->forgot_token_expires) < time()) {
			throw new AppException(AppExceptionType::$FORGOT_TOKEN_EXPIRED);
		}

		$user->forgot_token = null;
		$user->forgot_token_expires = null;
		$user->password = password_hash($this->request->input('password'), PASSWORD_BCRYPT);

		return $user->save();
	}
}