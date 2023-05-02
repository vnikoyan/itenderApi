<?php

// Define the namespace
namespace App\Repositories\UserCategories;

// Include any required classes, interfaces etc...
use DB;
use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;

class UserCpvsRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\UserCategories\UserCpvs';
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $id
	 * @return object
	 */
	function retrieveById($id)
	{
		return $this->find($id);
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $group_id
	 * @return Participant $participant
	 */
	function getByUserId($provider_id)
	{
        return $this->where("user_id", $provider_id)->with('cpv')->get();
	}
}
