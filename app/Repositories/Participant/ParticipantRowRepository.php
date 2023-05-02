<?php

// Define the namespace
namespace App\Repositories\Participant;

use DB;
use Prettus\Repository\Eloquent\BaseRepository;


class ParticipantRowRepository extends BaseRepository
{
    /**
     * Returns the name of the model class to be
     * used by this repository.
     *
     * @return string
     */
    function model()
    {
        return 'App\Models\Participant\ParticipantRow';
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
        return $this->where("organize_id", $organize_id)->groupBy("group_id")->orderBy("id")->paginate();
    }

    /**
     * Retrieves the user based on their id.
     *
     * @param integer $group_id
     * @return Participant $participant
     */
    function getByGroupId($group_id)
    {
        return $this->where("row_group_id", $group_id)->paginate();
    }
    /**
     * Retrieves the user based on their id.
     *
     * @param integer $organize_row_id
     * @return Participant $participant
     */
    function getByOrganizeRowId($organize_row_id)
    {
        return $this->where("organize_row_id", $organize_row_id)->paginate();
    }

    function getWinnerByOrganizeRowId($organize_row_id)
    {
        return $this::getWinner($organize_row_id);
    }

}
