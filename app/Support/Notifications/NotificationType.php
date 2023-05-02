<?php

// Define the namespace
namespace App\Support\Notifications;

// Include any required classes, interfaces etc...
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;

/**
 * Notification Type
 *
 * @author      Ben Carey <ben@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
abstract class NotificationType
{
	/**
	 * Notification Type Constants.
	 *
	 * @var integer
	 */
	const USER_FOLLOW                   = 1;
	const USER_SIGNED_UP                = 2;

	const POST                          = 3;
	const POST_LIKE                     = 4;
	const POST_COMMENT                  = 5;
	const POST_MENTION                  = 6;
	const POST_SHARE                    = 7;

	const SPOTLIGHT_INVITATION          = 8;
	const SPOTLIGHT_SELF_INVITATION     = 9;
	const SPOTLIGHT_UPDATED             = 10;
	const SPOTLIGHT_UPDATED_INDICATORS  = 11;
	const SPOTLIGHT_UPDATED_QUESTIONS   = 12;
	const SPOTLIGHT_FEEDBACK            = 13; // this one is splitted to 18,19
	const SPOTLIGHT_RESPONDED           = 14; // this one is splitted to 20,21
	const SPOTLIGHT_REMINDER            = 15;
	const SPOTLIGHT_QUESTION            = 16;
	const SPOTLIGHT_RATING              = 17;
	const SPOTLIGHT_FEEDBACK_RATING     = 18;
	const SPOTLIGHT_FEEDBACK_QUESTION   = 19;
	const SPOTLIGHT_RESPONDED_RATING    = 20;
	const SPOTLIGHT_RESPONDED_QUESTION  = 21;



	/**
     * Notification Type.
     *
     * @var integer
     */
    public $type;
	
	/**
     * Recipient ID.
     *
     * @var integer
     */
    public $recipient_id;
	
	/**
     * Sender ID.
     *
     * @var integer
     */
    public $sender_id;

	/**
	 * Reference ID.
	 *
	 * @var integer
	 */
	public $reference_id;

	/**
	 * Meta Information.
	 *
	 * @var array
	 */
	public $meta;

	/**
	 * $sec_reference_id.
	 *
	 * @var integer
	 */
	public $sec_reference_id;
	
	/**
	 * Notification Type Constructor.
	 *
	 * @param integer   $recipient_id
	 * @param integer   $sender_id
	 * @param integer   $reference_id
	 * @param array     $meta
	 * @param integer   $sec_reference_id
	 */
    public function __construct($recipient_id = null, $sender_id = null, $reference_id = null, $meta = [], $sec_reference_id = null)
    {
		$this->recipient_id 	= $recipient_id;
		$this->sender_id 		= $sender_id;
	    $this->reference_id 	= $reference_id;
		$this->meta 			= $meta;
		$this->sec_reference_id = $sec_reference_id;
	}
	
	/**
	 * Validate the supplied meta information.
	 *
	 * @return bool
	 */
	abstract function validate();

	/**
	 * Return the formatted data for the INSERT statement.
	 *
	 * @return array
	 */
	public function data()
	{
		return [
			'type_id' 		    => $this->type,
			'recipient_id' 	    => $this->recipient_id,
			'sender_id' 	    => $this->sender_id,
			'reference_id'  	=> $this->reference_id,
			'sec_reference_id'  => $this->sec_reference_id,
			'meta' 			    => $this->meta()
		];
	}
	
	/**
	 * Return the encoded meta data.
	 *
	 * @throws AppException
	 * @return string
	 */
	public function meta()
	{
		if (!$this->validate()) {
			throw new AppException(AppExceptionType::$INVALID_NOTIFICATION);
		}

		return json_encode($this->meta);
	}
}