<?php


namespace App\Repositories\Settings;


use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;

class PackageRepository extends BaseRepository
{
    /**
     * Returns the name of the model class to be
     * used by this repository.
     *
     * @return string
     */
    function model()
    {
        return 'App\Models\Package\Package';
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


}
