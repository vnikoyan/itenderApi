<?php

// Define the namespace
namespace App\Repositories\Organize;

// Include any required classes, interfaces etc...

use App\Jobs\ProcessNewTenderAdded;
use App\Models\Organize\OrganizeItender;
use DB;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Procurement\ProcurementPlanDetails;
use App\Models\Suggestions\Suggestions;
use App\Models\Tender\TenderState;

class OrganizeItenderRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	*/
	function model()
	{
		return 'App\Models\Organize\OrganizeItender';
	}
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  int $id
	 * @return Organize
    */
	function retrieveById(int $id)
	{
        return $this->with('procurement')->where('is_canceled', 0)->with('organizeRows')->findOrFail($id);
	}
    function remove(int $id)
	{
        $organize = $this->findOrFail($id);
        TenderState::where('one_person_organize_id', $id)->delete();
        $organize->delete();
	}
    /**
	 * Cancel Organize
	 *
	 * @param  int $id
	 * @return Organize
    */
	function cancel(int $id, string $cancel_reason)
	{
        $suggestions = Suggestions::where('organize_id', $id)->with('provider')->get();
        $organize = OrganizeItender::with('user')->find($id);
        $organisation = $organize->user->organisation;
        $cancel_reason_text = $cancel_reason === 'not_requirement_purchase' ? 'դադարել է գոյություն ունենալ գնման պահանջը' : 'անհրաժեշտություն է առաջացել փոփոխել կազմակերպված մրցույթի պայմանները';
        if($organisation->id_card_number){
            $organisation_name = $organisation->name;
        } else {
            $organisation_name = "«{$organisation->name}» $organisation->company_type";
        }
        foreach ($suggestions as $suggestion) {
            $email = $suggestion->provider->email;
            $data = new \stdClass();
            $data->subject = "Ծանուցում iTender մրցույթը չեղարկելու մասին";
            $data->email = trim($email);
            $data->text = "
            <div style='display: none; max-height: 0px; overflow: hidden;'>
                Ծանուցում iTender մրցույթը չեղարկելու մասին
                </div>
                <div style='display: none; max-height: 0px; overflow: hidden;'>
                &#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
            </div>
            <p>Հարգելի գործընկեր</p><br>
            <p>Կազմակերպիչը` ".$organisation_name."</p>
            <p>Գնման առարկան՝ ".htmlentities($organize->name)."</p></br>
            <p>Ծածկագիրը՝ ".$organize->code."</p></br>
            <p>Սկիզբ՝ ".date("Y-m-d H:i",strtotime($organize->updated_at))."</p></br>
            <p>Վերջնաժամկետը՝ ".date("Y-m-d H:i",strtotime($organize->opening_date_time))."</p>
            <p>Տեղեկացնում ենք, որ վերը նշված տենդերը չեղարկվել է՝ <b>{$cancel_reason_text}</b> /հիմնավորում՝ Կանոնակարգի 9․2 կետ/։</p>
            <p>Հարգանքով՝ iTender թիմ</p>";
            ProcessNewTenderAdded::dispatch($data);
        }
        TenderState::where('one_person_organize_id', $id)->delete();
        $organize->is_canceled = true;
        $organize->cancel_reason = $cancel_reason;
        $organize->save();
	}
    /**
     * Retrieves the user based on their id.
     *
     * @param int $procurement_id
     * @return Procurement $procurements
    */
	function getByProcurementById(int $procurement_id)
	{
        return $this->where("procurement_id",$procurement_id)->where('is_canceled', 0)->with('procurement')->orderBy("created_at",'desc')->paginate();
	}
    /**
     * Retrieves the user based on their id.
     *
     * @param int $user_id
     * @return Procurement $procurements
    */
	function getByProcurementByUser(int $user_id)
	{
        return $this->where("user_id",$user_id)->where('is_canceled', 0)
                        ->with('procurement')
                        ->orderBy("created_at",'desc')
                        ->get();
	}
    /**
     * Retrieves the user based on their id.
     *
     * @param  int $id
     * @return Organize
    */
	function getAllData(int $id){
        return $this->where('is_canceled', 0)->with('procurement')->with('organizeRow')->with('organizeRowPercents')->findOrFail($id);
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
    
        $data = $this->where('is_canceled', 0)->with('procurement')->with('organizeRow')->with('user')->get();

        return $data;
    }

}
