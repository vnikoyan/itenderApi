<?php

// Define the namespace
namespace App\Repositories\Settings;

// Include any required classes, interfaces etc...
use DB;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\Cerberus\Contracts\AuthProvider;


class UnitsRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Settings\Units';
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
	 * @param  integer $id
	 * @return string
	 */
	function categorytoTreeDescendants($cal_id)
	{
		return $this->orderBy("_lft")->descendantsAndSelf($cal_id)->toTree();
	}
	/**
	 * Search for top users based on the filter.
	 *
	 * @param $filter
	 * @return Builder
	 */
	public function top($filter = '')
	{

	}
}
