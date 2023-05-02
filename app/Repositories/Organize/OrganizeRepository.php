<?php

// Define the namespace
namespace App\Repositories\Organize;

// Include any required classes, interfaces etc...

use App\Models\Procurement\ProcurementPlanDetails;
use DB;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\VueTable\EloquentVueTables;
use Carbon\Carbon;

class OrganizeRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	*/
	function model()
	{
		return 'App\Models\Organize\Organize';
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  int $id
	 * @return Organize
    */
	function retrieveById(int $id)
	{
        return $this->with('procurement')->with('participants')->with('organizeRows')->findOrFail($id);
	}
    /**
	 * Cancel Organize
	 *
	 * @param  int $id
	 * @return Organize
    */
	function cancel(int $id)
	{
        $organize = $this->with('procurement')->with('participants')->with('organizeRows')->findOrFail($id);
        foreach ($organize->organizeRows as $row) {
            if(!$row->is_from_outside){
                $current_organize_count = $row->count;
                $detail_id = $row->procurementPlan->details[0]->id;
                $plan_detail = ProcurementPlanDetails::findOrFail($detail_id);
                $plan_detail->organize_count = $plan_detail->organize_count - $current_organize_count;
                $plan_detail->save();
            }
        }
        $organize->delete();
	}
    /**
     * Retrieves the user based on their id.
     *
     * @param int $procurement_id
     * @return Procurement $procurements
    */
	function getByProcurementById(int $procurement_id)
	{
        return $this->where("procurement_id",$procurement_id)->with('procurement')->orderBy("created_at",'desc')->paginate();
	}
    /**
     * Retrieves the user based on their id.
     *
     * @param int $user_id
     * @return Procurement $procurements
    */
	function getByProcurementByUser(int $user_id)
	{
        return $this->where("user_id",$user_id)
                        ->with('procurement')
                        ->orderBy("updated_at",'desc')
                        ->paginate();
	}
    /**
     * Retrieves the user based on their id.
     *
     * @param  int $id
     * @return Organize
    */
	function getAllData(int $id){
        return $this->with('procurement')->with('organizeRows')->with('organizeRowPercents')->findOrFail($id);
	}

    function getAll()
    {
        $vuetable = new EloquentVueTables();
        return $vuetable->get($this->where('publication', '!=', '')->with('procurement')->with('organizeRows')->with('user'), ['*'], ["title" => ["code","name"]]);
    }
}
