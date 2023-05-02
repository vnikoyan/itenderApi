<?php

// Include any required classes, interfaces etc...
use App\Support\Exceptions\AppExceptionType;

return [

    // General Errors
	AppExceptionType::$GENERAL_ERROR['code'] 			    => "General Error",

    // Development Errors
	AppExceptionType::$INVALID_ENDPOINT['code'] 			=> 'Invalid Endpoint',
	
	// JWT Token Errors
	AppExceptionType::$TOKEN_EXPIRED['code'] 			    => 'Token Expired',
	AppExceptionType::$INVALID_TOKEN['code'] 			    => 'Invalid token supplied',
	AppExceptionType::$PAYLOAD_EXCEPTION['code'] 		    => 'Payload Exception',
	AppExceptionType::$TOKEN_BLACKLISTED['code'] 		    => 'Token Blacklisted',
	
	// HTTP Errors
	AppExceptionType::$BAD_REQUEST['code'] 				    => 'Bad Request',
	AppExceptionType::$UNAUTHORIZED['code'] 				=> 'Unauthorized',
	AppExceptionType::$NOT_FOUND['code'] 				    => 'Not Found',
	AppExceptionType::$MAINTENANCE_MODE['code'] 		    => 'App is currently in maintenance mode, please try again a little later',
	
    // API Errors
	AppExceptionType::$NO_TOKEN['code'] 					=> 'No valid token supplied',
	AppExceptionType::$VALIDATION_ERROR['code'] 			=> 'Validation Error',
	AppExceptionType::$INVALID_INPUT['code'] 			    => 'Invalid Input',
	AppExceptionType::$INVALID_PARAMETERS['code'] 		=> 'Invalid Parameters',
	AppExceptionType::$INVALID_NOTIFICATION['code'] 		=> 'Invalid Notification',

	// Authentication Errors
	AppExceptionType::$WRONG_CREDENTIALS['code'] 		    => 'Login Failed',
	AppExceptionType::$FACEBOOK_ACCOUNT['code'] 		    => 'Facebook Account',
	AppExceptionType::$INVALID_FORGOT_TOKEN['code'] 		=> 'Invalid token supplied',
	AppExceptionType::$FORGOT_TOKEN_EXPIRED['code'] 		=> 'Token has expired',
	AppExceptionType::$NOT_CONFIRMED['code'] 				=> 'Email is not confirmed',
	AppExceptionType::$BLOCKED['code'] 						=> 'Account is blocked',

	// File Errors
	AppExceptionType::$CLOUD_UPLOAD_ERROR['code'] 	    => 'Failed to upload to cloud container',
	
	// User Errors
	AppExceptionType::$INCORRECT_PASSWORD['code'] 		=> 'Incorrect password entered',
	AppExceptionType::$EMAIL_NOT_FOUND['code'] 		    => 'Email address does not correspond to an existing user',
	AppExceptionType::$NO_FACEBOOK_EMAIL['code'] 		    => 'No email address returned by Facebook',
	AppExceptionType::$NO_CREDITS['code'] 		        => 'You have run out of spotlight credits',

	// Spotlight Errors
	AppExceptionType::$CREATE_SPOTLIGHT_FAILED['code']    => 'Failed to create spotlight',
	AppExceptionType::$UPDATE_SPOTLIGHT_FAILED['code']    => 'Failed to update spotlight',
	AppExceptionType::$SPOTLIGHT_MIN_AMIGOS['code']       => 'At least 3 amigos must be invited',
	AppExceptionType::$SPOTLIGHT_RESPONDED['code']        => 'You have already responded to this spotlight',
	AppExceptionType::$SPOTLIGHT_FEEDBACK_FAILED['code']  => 'Failed to send feedback',
	AppExceptionType::$SPOTLIGHT_CLOSED['code']           => 'This spotlight has been closed',
	AppExceptionType::$SPOTLIGHT_MAX_ELEMENTS['code']     => 'Too many elements selected',

	// Post Errors
	AppExceptionType::$RECIPIENT_NOT_AMIGO['code']        => 'Supplied recipient is not an amigo',

	// Payment Errors
	AppExceptionType::$INVALID_RECEIPT['code']            => 'Supplied receipt is invalid',
	AppExceptionType::$PURCHASE_CONSUMED['code']          => 'This payment has already been processed',
	AppExceptionType::$TRANSACTION_EXPIRED['code']        => 'This transaction has expired',
	AppExceptionType::$DUPLICATE_SUBSCRIPTION['code']     => 'This Apple account is already associated with a App Premium account for another user',

];