<?php

// Define the namespace
namespace App\Repositories\PurchasingProcess;

// Include any required classes, interfaces etc...
use DB;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\Cerberus\Contracts\AuthProvider;


class PurchasingProcessPercentRepository extends BaseRepository
{
    /**
     * Returns the name of the model class to be
     * used by this repository.
     *
     * @return string
     */
    function model()
    {
        return 'App\Models\PurchasingProcess\PurchasingProcessPercent';
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
    function getByPurchasingProcessId($purchasing_process_id)
    {
        return $this->where("purchasing_process_id",$purchasing_process_id)->paginate();
    }

}
