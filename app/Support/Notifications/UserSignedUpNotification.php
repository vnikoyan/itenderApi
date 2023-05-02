<?php

// Define the namespace
namespace App\Support\Notifications;

/**
 * User Signed Up Notification
 *
 * @author      Ben Carey <ben@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
class UserSignedUpNotification extends NotificationType
{
	/**
     * Notification Type ID.
     *
     * @var     int
     * @since   1.0.0
     */
    public $type = NotificationType::USER_SIGNED_UP;
	
	/**
	 * User Signed Up Notification Class Constructor.
	 */
    public function __construct(...$params)
    {
		parent::__construct(...$params);
    }
	
	/**
	 * Ensure all required data has been set.
	 *
	 * @return  boolean
	 */
    function validate()
    {
        return isset($this->meta['name'])
	        && isset($this->meta['username']);
    }
}