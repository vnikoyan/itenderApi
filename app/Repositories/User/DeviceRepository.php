<?php

// Define the namespace
namespace App\Repositories\User;

// Include any required classes, interfaces etc...
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Device Repository
 *
 * @author      Ben Carey <ben@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
class DeviceRepository extends BaseRepository 
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\User\Device';
	}

	/**
	 * Register a device for a user.
	 *
	 * @param   integer $user_id
	 * @param   string $token
	 * @param   string $uid
	 * @return  Device
	 */
	function register($user_id, $token, $uid)
	{
		$device = $this->scopeQuery(function($query) use ($token) {
			return $query->where('token', '=', $token);
		})->first();
		
		if ($device && $device->user_id == $user_id && $uid == $device->uid) {
			return $device;
		} elseif ($device && ($device->user_id != $user_id || $uid != $device->uid)) {
			$device->delete();
		}

		$device = $this->create(array(
			'user_id'   => $user_id,
			'token'     => (string) $token,
			'uid'       => (string) $uid
		));

		return $device;
	}
}