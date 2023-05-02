<?php

// Define the namespace
namespace App\Repositories\Participant;

// Include any required classes, interfaces etc...
use DB;
use Prettus\Repository\Eloquent\BaseRepository;


class ParticipantRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Participant\ParticipantGroup';
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
	 * @param  integer organize_id
	 * @return Participant $participant
	 */
	function getByOrganizeId($organize_id)
	{
        return $this->where("organize_id",$organize_id)
			->with('lots')
			->with('participant')
			->orderBy("id")
			->get();
	}

	function getWonLotsByOrganizeId($organize_id)
	{
        return $this->where("organize_id",$organize_id)
			->with('lots')
			->with('wonLots')
			->with('participant')
			->with('participantUser')
			->orderBy("id")
			->get();
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
	function suggestions($group_id)
	{
        return $this->where("group_id",$group_id)->paginate();
	}

	function getGroupsByOrganizeId($organize_id)
	{
        return $this::getGroups($organize_id);
	}
}
