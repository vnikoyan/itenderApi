<?php

// Define the namespace
namespace App\Repositories\Participant;

use DB;
use Prettus\Repository\Eloquent\BaseRepository;


class SelectedParticipantRepository extends BaseRepository
{
    /**
     * Returns the name of the model class to be
     * used by this repository.
     *
     * @return string
     */
    function model()
    {
        return 'App\Models\Participant\SelectedParticipants';
    }

    /**
     * Retrieves the user based on their id.
     *
     * @param integer $id
     * @return string
     */
    function retrieveById($id)
    {
        return $this->find($id);
    }

    /**
     * Retrieves the user based on their id.
     *
     * @param integer organize_id
     * @return Participant $participant
     */
    function getByOrganizeId($organize_id)
    {
        return $this->where("organize_row_id", $organize_id)->orderBy("id")->paginate();
    }

    /**
     * Retrieves the user based on their id.
     *
     * @param integer $group_id
     * @return Participant $participant
     */
    function getByGroupId($group_id)
    {
        return $this->where("participant_group_id", $group_id)->paginate();
    }
}
