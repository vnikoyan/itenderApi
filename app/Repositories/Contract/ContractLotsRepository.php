<?php

// Define the namespace
namespace App\Repositories\Contract;

// Include any required classes, interfaces etc...
use DB;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Contract\Contracts;

class ContractLotsRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Contract\ContractLots';
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $id
	 * @return array
	 */
	function getByClient(int $user_id, $q)
	{
		$contracts = Contracts::where("client_id",$user_id)->pluck('id')->toArray();
		return $this->select('contract_lots.id as contract_id', 'contract_lots.*')->whereIn('contract_id', $contracts)
		->with('organizeRow')
		->with('contract')
		->crossJoin('organize_row', 'organize_row.id', '=', 'contract_lots.organize_row_id')
		->crossJoin('procurement_plans', 'procurement_plans.id', '=', 'organize_row.procurement_plan_id')
		->crossJoin('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')
		->orderBy('cpv.code')
		->where(function ($query) use ($q) {
            $query->orWhere('cpv.code', 'like', '%' . $q . '%')->orWhere('cpv.name', 'like', '%' . $q . '%');
        });
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
	 * @param  integer organize_id
	 * @return Participant $participant
	 */
	function getByOrganizeId($organize_id)
	{
        return $this->where("organize_id",$organize_id)->groupBy("group_id")->orderBy("id")->paginate();
	}
}
