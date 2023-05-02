<?php


namespace App\Repositories\Organize;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Organize\OrganizeRowPercent;

class OrganizeRowPercentRepository extends BaseRepository
{
    /**
     * Returns the name of the model class to be
     * used by this repository.
     *
     * @return string
    */
    function model()
    {
        return 'App\Models\Organize\OrganizeRowPercent';
    }
    /**
     * Retrieves the user based on their id.
     *
     * @param  int $id
     * @return OrganizeRowPercent
     */
    function retrieveById(int $id)
    {
        return $this->find($id);
    }

    /**
     * Retrieves the user based on their id.
     *
     * @param int $organize_row_id
     * @return OrganizeRowPercent
     */
    function getByOrganizeRow(int $organize_row_id)
    {
        return $this->where("organize_row_id",$organize_row_id)->paginate();
    }


}
