<?php

// Define the namespace
namespace App\Repositories\Organize;

// Include any required classes, interfaces etc...
use DB;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Procurement\ProcurementPlanDetails;
use App\Models\Suggestions\Suggestions;
use App\Models\Tender\TenderState;

class OrganizeOnePersonRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	*/
	function model()
	{
		return 'App\Models\Organize\OrganizeOnePerson';
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  int $id
	 * @return Organize
    */
	function retrieveById(int $id)
	{
        return $this->with('procurement')->with('organizeRows')->findOrFail($id);
	}
    /**
	 * Cancel Organize
	 *
	 * @param  int $id
	 * @return Organize
    */
	function cancel(int $id)
	{
        $organize = $this->findOrFail($id);
        TenderState::where('one_person_organize_id', $id)->delete();
        Suggestions::where('organize_id', $id)->delete();
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
                        ->orderBy("created_at",'desc')
                        ->paginate();
	}
    /**
     * Retrieves the user based on their id.
     *
     * @param  int $id
     * @return Organize
    */
	function getAllData(int $id){
        return $this->with('procurement')->with('organizeRow')->with('organizeRowPercents')->findOrFail($id);
	}

    function getAll($params)
    {
        $sort       = $params->get('sort');
        $direction  = $params->get('direction');
        $query      = $params->get('query');
        $created_by = $params->get('created_by');
        $type       = $params->get('type');
        $limit      = (int)$params->get('limit');
        $page       = (int)$params->get('page');
        $created_at = $params->get('created_at');
        if ($sort !== null && $direction !== null) {
            $this->orderBy($sort, $direction);
        }
        if ($query !== null) {
            $this->where('code', 'like', '%' . $query . '%');
        }
        if ($created_by !== null) {
            $this->where('created_by', 'like', '%' . $created_by . '%');
        }
        if ($type !== null) {
            $this->where('type', 'like', '%' . $type . '%');
        }
        if ($created_at !== null) {
            $date_range = json_decode($created_at);
            $this->whereBetween('created_at', [Carbon::parse($date_range->start), Carbon::parse($date_range->end)]);
        }
    
        $this->offset($limit * ($page - 1))->limit($limit);
    
        $data = $this->with('procurement')->with('organizeRow')->with('user')->get();

        return $data;
    }

}
