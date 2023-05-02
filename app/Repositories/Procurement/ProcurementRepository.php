<?php

// Define the namespace
namespace App\Repositories\Procurement;

// Include any required classes, interfaces etc...
use DB;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\Cerberus\Contracts\AuthProvider;


class ProcurementRepository extends BaseRepository 
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Procurement\Procurement';
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
	 * @param  integer $organisation_id
	 * @return Procurement $procurements 
	 */
	function getByOrganisationId($organisation_id)
	{
        // return $this->orderBy("_lft")->descendantsAndSelf($cal_id)->toTree();
        // $this->shield->id()
        return $this->where("organisation_id",$organisation_id)->orderBy("year",'desc')->paginate();
	}
	
}