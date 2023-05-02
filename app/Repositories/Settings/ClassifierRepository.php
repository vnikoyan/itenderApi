<?php

// Define the namespace
namespace App\Repositories\Settings;

// Include any required classes, interfaces etc...
use DB;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\Cerberus\Contracts\AuthProvider;


class ClassifierRepository extends BaseRepository 
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 * 
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Settings\Classifier';
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