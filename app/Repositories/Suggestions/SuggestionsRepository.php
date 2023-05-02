<?php

// Define the namespace
namespace App\Repositories\Suggestions;

// Include any required classes, interfaces etc...

use App\Models\Organize\OrganizeItender;
use App\Models\Participant\ParticipantRow;
use DB;
use Carbon\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\VueTable\EloquentVueTables;
use Illuminate\Support\Facades\Log;

class SuggestionsRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Suggestions\Suggestions';
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
	 * Cancel suggestion.
	 *
	 * @param  integer $id
	 * @return object
	 */
	function cancel($id)
	{
		$user_id = auth('api')->user()->id;
		$suggestion = $this->find($id);
		$suggestion->is_signature = 1;
		$suggestion->responded = 0;

		ParticipantRow::where('participant_id', $user_id)
		->whereHas('group', function ($query) use ($suggestion) {
			return $query->where('organize_id', $suggestion->organize_id);
		})->update(['is_satisfactory' => 0]);

		$suggestion->save();
		return $suggestion;
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer organize_id
	 * @return Participant $participant
	 */
	function getByOrganizeId($organize_id)
	{
        return $this->where("organize_id", $organize_id)->orderBy("id")->get();
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $group_id
	 * @return Participant $participant
	 */
	function getByProviderId($provider_id)
	{
        return $this->where("provider_id", $provider_id)->paginate();
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $group_id
	 * @return Participant $participant
	 */
	function suggestions($group_id)
	{
        return $this->where("group_id", $group_id)->paginate();
	}

	function getGroupsByOrganizeId($organize_id)
	{
        return $this::getGroups($organize_id);
	}

	function getAll($params)
    {
        $vuetable = new EloquentVueTables();

        $responded  = (int)$params->get('responded');
        $is_signature  = (int)$params->get('is_signature');
        $provider_id  = (int)auth('api')->user()->id;
		$user_id = auth('api')->user()->id;

		$data = $this
			->with('client')
			->with('provider')
			->with('organize')
			->with('organizeItender')
			->where("provider_id", $provider_id)
			->where('responded', $responded)
			->where('is_signature', $is_signature)
			->orderBy('id', 'DESC');

		if($params->get('favorite') === 'true'){
			$data->whereHas('favorite', function($query) use ($user_id) {
				$query->where('user_id', $user_id);
			});
		}
		// return $vuetable->get($data, ['*'], []);

		return $vuetable->get($data, ['*'], [], ["organize" => ["name", 'code'], "organizeItender" => ["name", 'code']]);

    }
}
