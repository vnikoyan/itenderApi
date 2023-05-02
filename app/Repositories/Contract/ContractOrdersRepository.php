<?php

namespace App\Repositories\Contract;
use Prettus\Repository\Eloquent\BaseRepository;

use App\Models\Contract\ContractOrders;
use App\Models\Contract\Contracts;
use Illuminate\Support\Facades\Log;

class ContractOrdersRepository extends BaseRepository
{
    /**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Contract\ContractOrders';
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
     * @return ContractsOrders $procurements
    */
	function getByClient(int $user_id, string $status)
	{
		$status_array = $this->getStatusArray($status);
        $contracts = Contracts::where("client_id",$user_id)->pluck('id')->toArray();;
		return $this->whereIn('contract_id', $contracts)
						->whereIn('status', $status_array)
                        ->with('contract')
                        ->with('lots')
						->orderBy('id', 'DESC');

	}

	/**
     * Retrieves the user based on their id.
     *
     * @param int $user_id
     * @return ContractsOrders $procurements
    */
	function getByProvider(int $user_id, string $status)
	{
		$status_array = $this->getStatusArray($status);
        $contracts = Contracts::where("provider_user_id",$user_id)->pluck('id')->toArray();
		return $this->whereIn('contract_id', $contracts)
						->whereIn('status', $status_array)
                        ->with('contract')
                        ->with('lots')
						->orderBy('id', 'DESC');
	}

	function getStatusArray($status){
		switch ($status) {
			case 'active':
				return ['sended'];
			case 'canceled':
				return ['canceled'];
			case 'finished':
				return ['failed', 'completed'];
			default:
				break;
		}
	}
}
