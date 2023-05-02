<?php

// Define the namespace
namespace App\Support\Exceptions;


class AppExceptionType {

    // General Errors
    public static $GENERAL_ERROR        							= ['code' => 10001, 'http' => 400, 'report' => true];

    // Development Errors
    public static $INVALID_ENDPOINT     							= ['code' => 10101, 'http' => 400];
    public static $DATABASE_ERROR       							= ['code' => 10102, 'http' => 400];
	
	// JWT Token Errors
    public static $TOKEN_EXPIRED        							= ['code' => 10200, 'http' => 400];
    public static $INVALID_TOKEN        							= ['code' => 10201, 'http' => 400];
    public static $PAYLOAD_EXCEPTION    							= ['code' => 10202, 'http' => 400];
    public static $TOKEN_BLACKLISTED    							= ['code' => 10203, 'http' => 400];
	
	// HTTP Errors
    public static $BAD_REQUEST          							= ['code' => 10300, 'http' => 400];
    public static $UNAUTHORIZED         							= ['code' => 10301, 'http' => 401];
    public static $NOT_FOUND            							= ['code' => 10302, 'http' => 404];
	public static $MAINTENANCE_MODE            			            = ['code' => 10303, 'http' => 503];
	
    // API Errors
    public static $NO_TOKEN             							= ['code' => 10400, 'http' => 400];
    public static $VALIDATION_ERROR     							= ['code' => 10401, 'http' => 400];
    public static $INVALID_INPUT        							= ['code' => 10402, 'http' => 400];
    public static $INVALID_PARAMETERS   							= ['code' => 10403, 'http' => 400];
    public static $INVALID_NOTIFICATION   							= ['code' => 10404, 'http' => 400];

    // Authentication Errors
    public static $WRONG_CREDENTIALS      							= ['code' => 10500, 'http' => 400];
    public static $FACEBOOK_ACCOUNT      							= ['code' => 10501, 'http' => 400];
    public static $INVALID_FORGOT_TOKEN      						= ['code' => 10502, 'http' => 400];
    public static $FORGOT_TOKEN_EXPIRED      				        = ['code' => 10503, 'http' => 400];
    public static $NOT_CONFIRMED      							    = ['code' => 10504, 'http' => 400];
    public static $BLOCKED      							        = ['code' => 10505, 'http' => 400];

    // File Errors
    public static $CLOUD_UPLOAD_ERROR      							= ['code' => 10600, 'http' => 400, 'report' => true];
    
    // User Errors
    public static $INCORRECT_PASSWORD                               = ['code' => 10700, 'http' => 400];
    public static $EMAIL_NOT_FOUND                                  = ['code' => 10701, 'http' => 400];
    public static $NO_FACEBOOK_EMAIL                                = ['code' => 10702, 'http' => 400];
    public static $NO_CREDITS                                       = ['code' => 10703, 'http' => 400];
    public static $NO_PERMISSION                                    = ['code' => 10807, 'http' => 403];

    // Spotlight Errors

    public static $CREATE_SPOTLIGHT_FAILED                          = ['code' => 10800, 'http' => 400];
    public static $UPDATE_SPOTLIGHT_FAILED                          = ['code' => 10801, 'http' => 400];
    public static $SPOTLIGHT_MIN_AMIGOS                             = ['code' => 10802, 'http' => 400];
    public static $SPOTLIGHT_RESPONDED                              = ['code' => 10803, 'http' => 400];
    public static $SPOTLIGHT_FEEDBACK_FAILED                        = ['code' => 10804, 'http' => 400];
    public static $SPOTLIGHT_CLOSED                                 = ['code' => 10805, 'http' => 400];
    public static $SPOTLIGHT_MAX_ELEMENTS                           = ['code' => 10806, 'http' => 400];

	// Post Errors
	public static $RECIPIENT_NOT_AMIGO                              = ['code' => 10900, 'http' => 400];

	// Payment Errors
	public static $INVALID_RECEIPT                                  = ['code' => 11000, 'http' => 400];
    public static $PURCHASE_CONSUMED                                = ['code' => 11001, 'http' => 400];
    public static $TRANSACTION_EXPIRED                              = ['code' => 11002, 'http' => 400];
    public static $DUPLICATE_SUBSCRIPTION                           = ['code' => 11003, 'http' => 400];
	
}