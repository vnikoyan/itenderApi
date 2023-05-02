<?php

// Define the namespace
namespace App\Repositories\Contract;

// Include any required classes, interfaces etc...
use App\Support\VueTable\EloquentVueTables;
use DB;
use Illuminate\Support\Facades\Log;
use Prettus\Repository\Eloquent\BaseRepository;

class ContractsRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Contract\Contracts';
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
     * Retrieves the user based on their id.
     *
     * @param int $user_id
     * @return Procurement $procurements
    */
	function getByClient(int $user_id, $q)
	{
		$vuetable = new EloquentVueTables();

		$contracts =  $this->select('contracts.*')
                        ->with('organize')
                        ->with('participant')
                        ->with('participantUsers')
                        ->with('lots')
						->orderBy('id', 'DESC')
						->where("client_id", $user_id);

		return $vuetable->get($contracts, ['*'], ['code', 'name'], ['participantUsers' => ['name']]);
	}
	/**
     * Retrieves the user based on their id.
     *
     * @param int $user_id
     * @return Procurement $procurements
    */
	function getByProvider(int $user_id, $q)
	{
		$vuetable = new EloquentVueTables();

        $contracts = $this->select('contracts.*')
                        ->with('organize')
                        ->with('clientUser')
                        ->with('lots')
						->orderBy('id', 'DESC')
						->where("is_sign", 1)
						->where("provider_user_id", $user_id);

		return $vuetable->get($contracts, ['*'], ['code', 'name'], ['clientUser' => ['name']]);
	}
	/**
     * Retrieves the user based on their id.
     *
     * @param int $user_id
     * @return Procurement $procurements
    */
	function getRequestsByProvider(int $user_id, $q)
	{
        return $this->select('contracts.*')
                        ->with('organize')
                        ->with('participant')
                        ->with('participantUsers')
                        ->with('lots')
						->where("is_sign", 0)
						->where("provider_user_id", $user_id);
			
						// return $this->select('contracts.*')
						// ->with('organize')
						// ->with('participant')
						// ->with('participantUsers')
						// ->with('lots')
						// ->crossJoin('contract_lots', 'contract_lots.contract_id', '=', 'contracts.id')
						// ->crossJoin('organize_row', 'organize_row.id', '=', 'contract_lots.organize_row_id')
						// ->crossJoin('procurement_plans', 'procurement_plans.id', '=', 'organize_row.procurement_plan_id')
						// ->crossJoin('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')
						// ->orderBy('cpv.code')
						// ->groupBy('id')
						// ->where("is_sign", 0)
						// ->where("provider_user_id", $user_id)
						// ->where(function ($query) use ($q) {
						// 	$query->orWhere('cpv.code', 'like', '%' . $q . '%')->orWhere('cpv.name', 'like', '%' . $q . '%')->orWhere('contracts.code', 'like', '%' . $q . '%');
						// });
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
	function getByGroupId($group_id)
	{
        return $this->where("group_id",$group_id)->paginate();
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $group_id
	 * @return Participant $participant
	 */
	function suggestions($group_id)
	{
        return $this->where("group_id",$group_id)->paginate();
	}

	function getGroupsByOrganizeId($organize_id)
	{
        return $this::getGroups($organize_id);
	}
}
