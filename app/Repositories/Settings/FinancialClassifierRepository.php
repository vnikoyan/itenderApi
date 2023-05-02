<?php


namespace App\Repositories\Settings;

// Include any required classes, interfaces etc...
use Prettus\Repository\Eloquent\BaseRepository;


class FinancialClassifierRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Settings\FinancialClassifier';
	}

	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $id
	 * @return string
	 */
	function retrieveById($id){
		return $this->find($id);
	}

	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $id
	 * @return string
	 */
	function getByCpvId($id){
		return $this->whereHas('cpv', function ($query)  use($id){
			return $query->where('cpv_id',$id);
		})->get();
	}

}