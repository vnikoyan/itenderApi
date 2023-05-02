<?php

// Define the namespace
namespace App\Repositories\PurchasingProcess;

// Include any required classes, interfaces etc...
use DB;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\Cerberus\Contracts\AuthProvider;


class PurchasingProcessRepository extends BaseRepository
{
	/** PurchasingProcessPercentRepository
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\PurchasingProcess\PurchasingProcess';
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $id
	 * @return string
	 */
	function retrieveById($id)
	{
		return $this->find($id);
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer organize_id
	 * @return Participant $participant
	 */
	function getByOrganisationId($organisation_id)
	{
        return $this->where("organisation_id",$organisation_id)->paginate();
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $group_id
	 * @return Participant $participant
	 */
	function getByGroupId($group_id)
	{
        return $this->where("group_id",$group_id)->paginate();
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $group_id
	 * @return Participant $participant
	 */
	function deleteUser(int $id,int $user_id)
	{
	    return $this->find($id)->user()->delete($user_id);
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $group_id
	 * @return Participant $participant
	 */
	function suggestions()
	{
        return $this->whereHas('user', function ($query) {
            return $query->where('user_id', '=', auth('api')->user()->id);
        })->paginate();
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $group_id
	 * @return Participant $participant
	 */
	function notSuggestions()
	{
        return $this->whereHas('user', function ($query) {
            return $query->where('user_id', '!=', auth('api')->user()->id);
        })->paginate();
	}
}
