<?php

// Define the namespace
namespace App\Support\Notifications;

/**
 * Spotlight Updated Indicators Notification
 *
 * @author      Ben Carey <ben@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
class SpotlightUpdatedIndicatorsNotification extends NotificationType
{
	/**
     * Notification Type ID.
     *
     * @var     int
     * @since   1.0.0
     */
    public $type = NotificationType::SPOTLIGHT_UPDATED_INDICATORS;
	
	/**
	 * Spotlight Updated Indicators Notification Class Constructor.
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
	        && isset($this->meta['title']);
    }
}