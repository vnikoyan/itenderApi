<?php

// Define the namespace
namespace App\Http\Requests;

// Include any required classes, interfaces etc...
use Illuminate\Foundation\Http\FormRequest;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;

abstract class AbstractRequest extends FormRequest
{
	/**
	 * The sanitized input.
	 *
	 * @var array
	 */
	protected $sanitized;

	// /**
	//  * Prepend the validator with the sanitize method if it exists.
	//  *
	//  * @return bool
	//  */
	public function validator($factory)
	{
	// 	// print_r($this->validationData());
		$validation =  $factory->make(
			$this->sanitizeInput(), $this->container->call([$this, 'rules']), $this->messages()
		);
		if(!empty($validation->errors()->all())){
			return $this->response($validation->errors()->all());
		}
		return $validation;
	}

	/**
	 * Sanitize the input.
	 *
	 * @return array
	 */
	protected function sanitizeInput()
	{
		if (method_exists($this, 'sanitize'))
		{
			return $this->sanitized = $this->container->call([$this, 'sanitize']);
		}

		return $this->all();
	}

	/**
	 * Get sanitized input.
	 *
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	public function sanitized($key = null, $default = null)
	{
		$input = is_null($this->sanitized) ? $this->all() : $this->sanitized;
		return array_get($input, $key, $default);
	}
	
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Throw an exception on validation fail.
	 *
	 * @param   array $errors
	 * @throws  AppException
	 * @return  void
	 */
	public function response(array $errors)
	{
		throw new AppException(AppExceptionType::$VALIDATION_ERROR, $errors);
	}

	/**
	 * Throw an exception on authorisation fail.
	 *
	 * @throws AppException
	 * @return  void
	 */
	public function forbiddenResponse()
	{
		throw new AppException(AppExceptionType::$UNAUTHORIZED);
	}
}
