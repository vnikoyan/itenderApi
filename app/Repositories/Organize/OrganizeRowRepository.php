<?php

// Define the namespace
namespace App\Repositories\Organize;

// Include any required classes, interfaces etc...
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Procurement\ProcurementPlanDetails;
use Illuminate\Support\Facades\Log;

class OrganizeRowRepository extends BaseRepository
{
    /**
     * Returns the name of the model class to be
     * used by this repository.
     *
     * @return string
     */
    function model()
    {
        return 'App\Models\Organize\OrganizeRow';
    }
    /**
     * Retrieves the user based on their id.
     *
     * @param  int $id
     * @return string
    */
    function retrieveById(int $id)
    {
        $organize = $this->with('procurementPlan')
            ->with('organize')
            ->with('organizeRowPercent')
            ->with('procurementPlan')
            ->find($id);
        // $procurement = ProcurementPlanDetails::find($organize->plan_details_id);
        // $procurement->organize_count = $procurement->organize_count - $organize->count;
        // $procurement->save();
        return $organize;
    }
    /**
     * Retrieves the user based on their id.
     *
     * @param int $organize_id
     * @return OrganizeRow
    */
    function getByOrganize(int $organize_id)
    {
        return $this->where("organize_row.organize_id",$organize_id)
              ->with('organizeRowPercent')
              ->with('procurementPlan')
              ->with('winner')
              ->with('participants')
              ->with('organize')
              ->groupBy('organize_row.id')
              ->orderBy("organize_row.view_id",'ASC')
              ->get();
    }

    function getByOrganizeWithParticipmants($organize_id)
    {
        return $this->where("organize_id",$organize_id)
        ->with('procurementPlan')
        ->with('organize')
        ->with('organizeRowPercent')
        ->with('procurementPlan')
        ->with('participants')
        ->with('winner')
        ->get();
    }

}
