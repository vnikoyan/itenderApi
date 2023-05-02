<?php

// Define the namespace
namespace App\Repositories\Procurement;

// Include any required classes, interfaces etc...
use App\Support\VueTable\EloquentVueTables;
use DB;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Cpv\Cpv;
use App\Models\Procurement\ProcurementPlanDetails;

class ProcurementPlanRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	*/
	function model()
	{
		return 'App\Models\Procurement\ProcurementPlan';
	}

    private  $procurementPlan;

    public function __construct(Application $app){
        parent::__construct($app);
        if(auth('api')->user()){
            $this->procurementPlan  = $this->where("procurement_plans.organisation_id",auth('api')->user()->parent_id);
        }
    }
	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $id
	 * @return string
    */
	function retrieveById($id)
	{
		return $this->procurementPlan->findOrFail($id);
	}
	function getByPermissions($id = false)
	{
		$procurementPlan =  $this->procurementPlan->where("user_id_".auth('api')->user()->divisions,auth('api')->user()->id);

		if($id){
			$procurementPlan->where("procurement_id",$id);
		}
		return $procurementPlan->paginate(10);
	}

	function getByQuery(int $procurement_id,$request)
	{
         $procurementPlan = $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
            ->where(function ($query) {
                $user_id = auth('api')->user()->id;
                $query
                    ->orWhere('procurement_plans.user_id_1', $user_id)
                    ->orWhere('procurement_plans.user_id_2', $user_id)
                    ->orWhere('procurement_plans.user_id_3', $user_id)
                    ->orWhere('procurement_plans.user_id_4', $user_id)
                    ->orWhere('procurement_plans.user_id_5', $user_id);
            })
              ->select(
                  "procurement_plans_details.id as id",
                  "procurement_plans.id as plan_row_id",
                  "cpv.id as cpv_id",
                  "cpv.name as cpv_name",
                  "cpv.code as cpv_code",
                  "procurement_plans.cpv_drop as cpv_drop",
                  "procurement_plans.cpv_type as cpv_type",
                  "procurement_plans.specifications_id as specifications_id",
                  "procurement_plans.specifications_id as specifications_id",
                  "procurement_plans.is_condition as is_condition",
                  "procurement_plans.condition_type as condition_type",
                  "procurement_plans.unit as unit",
                  "procurement_plans_details.count as count",
                  "procurement_plans_details.date as date",
                  "procurement_plans_details.unit_amount as unit_amount",
                  "procurement_plans_details.type as type",
                  "procurement_plans_details.organize_count as organize_count",
                  "procurement_plans_details.classifier_id as classifier_id",
                  "procurement_plans_details.financial_classifier_id as financial_classifier_id",
                  "procurement_plans_details.classifier as classifier",
                  "procurement_plans_details.financial_classifier as financial_classifier",
                  "procurement_plans.order_index as order_index",
                  "procurement_plans.status as status",
                  "procurement_plans.user_id_1 as user_id_1",
                  "procurement_plans.user_id_2 as user_id_2",
                  "procurement_plans.user_id_3 as user_id_3",
                  "procurement_plans.user_id_4 as user_id_4",
                  "procurement_plans.user_id_5 as user_id_5"
              )
              ->join('procurement_plans_details','procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
              ->join('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id');


            // if(!empty($request["financial_classifier_id"])){
            //     $procurementPlan->where("procurement_plans_details.financial_classifier_id",$request["financial_classifier_id"]);
            // }
            // if(!empty($request["classifier_id"])){
            //     $procurementPlan->where("procurement_plans_details.classifier_id",$request["classifier_id"]);
            // }
            if(!empty($request["financial_classifier"])){
                $procurementPlan->where("procurement_plans_details.financial_classifier",$request["financial_classifier"]);
            }
            if(!empty($request["classifier"])){
                $procurementPlan->where("procurement_plans_details.classifier",$request["classifier"]);
            }
            if(!empty($request["cpv_type"])){
                $procurementPlan->where("procurement_plans.cpv_type",$request["cpv_type"]);
            }
            if(!empty($request["type"])){
                $procurementPlan->where("procurement_plans_details.type",$request["type"]);
            }

            if(!empty($request["query"])){
            $cpvQuery = trim($request["query"]);
            $procurementPlan->where(function($query) use ($cpvQuery){
                $query->where('cpv.code', 'LIKE', "%{$cpvQuery}%");
                $query->orWhere('cpv.name', 'LIKE', "%{$cpvQuery}%");
            });
            }

            $procurementPlan->groupBy('procurement_plans_details.id');
            $procurementPlan->orderBy("cpv.code");

    		return $procurementPlan->get();
	}
	function getByCpvType(int $id,int $cpv_type){
		$procurementPlan =  $this->procurementPlan
        ->where(function ($query) {
            $user_id = auth('api')->user()->id;
            $query
                ->orWhere('procurement_plans.user_id_1', $user_id)
                ->orWhere('procurement_plans.user_id_2', $user_id)
                ->orWhere('procurement_plans.user_id_3', $user_id)
                ->orWhere('procurement_plans.user_id_4', $user_id)
                ->orWhere('procurement_plans.user_id_5', $user_id);
        })->where("user_id_".auth('api')->user()->divisions,auth('api')->user()->id);
		$procurementPlan->where("procurement_id",$id)->where("cpv_type",$cpv_type);
		return $procurementPlan->paginate(10);
	}
    function getByPermissionsDataTable($id = false,$type){
        $vuetable = new EloquentVueTables();
        $procurementPlan =  $this->procurementPlan->with('cpv')
        ->where(function ($query) {
            $user_id = auth('api')->user()->id;
            $query
                ->orWhere('procurement_plans.user_id_1', $user_id)
                ->orWhere('procurement_plans.user_id_2', $user_id)
                ->orWhere('procurement_plans.user_id_3', $user_id)
                ->orWhere('procurement_plans.user_id_4', $user_id)
                ->orWhere('procurement_plans.user_id_5', $user_id);
        })->where("budget_type",$type)->where("user_id_".auth('api')->user()->divisions,auth('api')->user()->id);
        if($id){
            $procurementPlan->where("procurement_id",$id);
        }
        return $vuetable->get($procurementPlan,
            ["id","order_index", "status","cpv_id","user_id_".(auth('api')->user()->divisions-1). " as user_id", "cpv_drop", "unit", "specifications_id", "count", "unit_amount", "type", "is_condition", "condition_type", "date"]
            ,["cpv"=>["name","code"]]);
    }
    function getValidType(int $id,int $cpv_id){
        $getCpv = Cpv::selectRaw("*, SUBSTR(code, 1,3) as code")->findOrFail($cpv_id);
        $ids = Cpv::where(\DB::raw('SUBSTR(code, 1, 3)'), '=' , $getCpv->code)->whereDoesntHave("childrenOne")->pluck('id');
        $totalIs = $this->procurementPlan
            ->whereIn("cpv_id",$ids)
            ->where(function ($query) {
                $user_id = auth('api')->user()->id;
                $query
                    ->orWhere('procurement_plans.user_id_1', $user_id)
                    ->orWhere('procurement_plans.user_id_2', $user_id)
                    ->orWhere('procurement_plans.user_id_3', $user_id)
                    ->orWhere('procurement_plans.user_id_4', $user_id)
                    ->orWhere('procurement_plans.user_id_5', $user_id);
            })
            ->where("procurement_id",$id)
            ->whereHas('details', function ( $query) {
                $query->where('type', '1');
            })->pluck('id');
        $total = ProcurementPlanDetails::select(DB::raw('sum(count * unit_amount) as total'))->whereIn("procurement_plans_id",$totalIs)->first();

        return $total;

	}

	function getFilePdf($procurement_id){
        $procurementPlan =  $this->procurementPlan->where("user_id_".auth('api')->user()->divisions,auth('api')->user()->id);
        return $procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)->get();

    }
	function getByFinancialClassifiers(int $procurement_id,int $financial_classifier_id,$cpv_type){
	     return $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
            ->where(function ($query) {
                $user_id = auth('api')->user()->id;
                $query
                    ->orWhere('procurement_plans.user_id_1', $user_id)
                    ->orWhere('procurement_plans.user_id_2', $user_id)
                    ->orWhere('procurement_plans.user_id_3', $user_id)
                    ->orWhere('procurement_plans.user_id_4', $user_id)
                    ->orWhere('procurement_plans.user_id_5', $user_id);
            })
              ->select(
                  "procurement_plans_details.id as id",
                  "procurement_plans.id as plan_row_id",
                  "cpv.id as cpv_id",
                  "cpv.name as cpv_name",
                  "cpv.code as cpv_code",
                  "procurement_plans.cpv_drop as cpv_drop",
                  "procurement_plans.cpv_type as cpv_type",
                  "procurement_plans.specifications_id as specifications_id",
                  "procurement_plans.specifications_id as specifications_id",
                  "procurement_plans.is_condition as is_condition",
                  "procurement_plans.condition_type as condition_type",
                  "procurement_plans.unit as unit",
                  "procurement_plans_details.organize_count as organize_count",
                  "procurement_plans_details.count as count",
                  "procurement_plans_details.date as date",
                  "procurement_plans_details.unit_amount as unit_amount",
                  "procurement_plans_details.type as type",
                  "procurement_plans_details.classifier_id as classifier_id",
                  "procurement_plans_details.financial_classifier_id as financial_classifier_id",
                  "procurement_plans_details.classifier as classifier",
                  "procurement_plans_details.financial_classifier as financial_classifier",
                  "procurement_plans.order_index as order_index",
                  "procurement_plans.status as status",
                  "procurement_plans.user_id_1 as user_id_1",
                  "procurement_plans.user_id_2 as user_id_2",
                  "procurement_plans.user_id_3 as user_id_3",
                  "procurement_plans.user_id_4 as user_id_4",
                  "procurement_plans.user_id_5 as user_id_5"
              )
              ->where("procurement_plans.cpv_type",$cpv_type)
              ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
              ->join('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')
              ->where('procurement_plans_details.financial_classifier_id',$financial_classifier_id)
              ->groupBy('procurement_plans_details.id')
              ->orderBy("cpv.code")
              ->paginate(10);
    }

	function getByClassifiers(int $procurement_id,int $classifier_id,$cpv_type){
	      return $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
            ->where(function ($query) {
                $user_id = auth('api')->user()->id;
                $query
                    ->orWhere('procurement_plans.user_id_1', $user_id)
                    ->orWhere('procurement_plans.user_id_2', $user_id)
                    ->orWhere('procurement_plans.user_id_3', $user_id)
                    ->orWhere('procurement_plans.user_id_4', $user_id)
                    ->orWhere('procurement_plans.user_id_5', $user_id);
            })
          ->where("cpv_type",$cpv_type)->whereHas('details', function ($query) use($classifier_id) {
                            $query->where('financial_classifier_id',$classifier_id);
	      })->paginate(10);
    }

	function getByFinancialClassifierId(int $procurement_id){
            /// view 1
	     return $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
                ->where(function ($query) {
                    $user_id = auth('api')->user()->id;
                    $query
                        ->orWhere('procurement_plans.user_id_1', $user_id)
                        ->orWhere('procurement_plans.user_id_2', $user_id)
                        ->orWhere('procurement_plans.user_id_3', $user_id)
                        ->orWhere('procurement_plans.user_id_4', $user_id)
                        ->orWhere('procurement_plans.user_id_5', $user_id);
                })
                ->select("financial_classifier.id as id","financial_classifier.title as title",
                    DB::raw('sum(procurement_plans_details.count * procurement_plans_details.unit_amount) as total')
                 )
                ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
                ->join('financial_classifier', 'financial_classifier.id', '=', 'procurement_plans_details.financial_classifier_id')
                ->groupBy('financial_classifier.id')
//                ->groupBy('cpv_type')
                ->get()->toArray();
    }
	function getByFinancialClassifierCuntCpvType(int $procurement_id,int $financial_classifier){
            /// view 1 totoal
	     return $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
                ->where(function ($query) {
                    $user_id = auth('api')->user()->id;
                    $query
                        ->orWhere('procurement_plans.user_id_1', $user_id)
                        ->orWhere('procurement_plans.user_id_2', $user_id)
                        ->orWhere('procurement_plans.user_id_3', $user_id)
                        ->orWhere('procurement_plans.user_id_4', $user_id)
                        ->orWhere('procurement_plans.user_id_5', $user_id);
                })
                ->select("cpv_type",
                    DB::raw('sum(procurement_plans_details.count * procurement_plans_details.unit_amount) as total')
                 )
                ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
                ->join('financial_classifier', 'financial_classifier.id', '=', 'procurement_plans_details.financial_classifier_id')
                ->where('financial_classifier_id',$financial_classifier)
                ->groupBy('cpv_type')
                ->get()->toArray();
    }


	function getByClassifierId(int $procurement_id){
	     //3-րդ view
	     return $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
                ->where(function ($query) {
                    $user_id = auth('api')->user()->id;
                    $query
                        ->orWhere('procurement_plans.user_id_1', $user_id)
                        ->orWhere('procurement_plans.user_id_2', $user_id)
                        ->orWhere('procurement_plans.user_id_3', $user_id)
                        ->orWhere('procurement_plans.user_id_4', $user_id)
                        ->orWhere('procurement_plans.user_id_5', $user_id);
                })
                ->select(
                    "classifier.id as id",
                    "classifier.title as title",
                    DB::raw('sum(procurement_plans_details.count * procurement_plans_details.unit_amount) as total')
                )
                ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
                ->join('classifier', 'classifier.id', '=', 'procurement_plans_details.classifier_id')
                ->groupBy('classifier.id')
                ->get()->toArray();
    }

	function getByClassifiersForFinancial(int $procurement_id,int $classifier_id)
	{
	     //3-րդ view
	     return $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
                ->where(function ($query) {
                    $user_id = auth('api')->user()->id;
                    $query
                        ->orWhere('procurement_plans.user_id_1', $user_id)
                        ->orWhere('procurement_plans.user_id_2', $user_id)
                        ->orWhere('procurement_plans.user_id_3', $user_id)
                        ->orWhere('procurement_plans.user_id_4', $user_id)
                        ->orWhere('procurement_plans.user_id_5', $user_id);
                })
                ->select(
                    "financial_classifier.id as id",
                    "financial_classifier.title as title",
                    DB::raw('sum(procurement_plans_details.count * procurement_plans_details.unit_amount) as total')
                )
                ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
                ->join('financial_classifier', 'financial_classifier.id', '=', 'procurement_plans_details.financial_classifier_id')
                ->where("procurement_plans_details.classifier_id",$classifier_id)
                ->groupBy('financial_classifier.id')
                ->get()->toArray();
	}
	function getByClassifiersForFinancialCpvType(int $procurement_id,int $classifier_id,int $financial_classifier)
	{
	     //3-րդ view
	     return $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
                ->where(function ($query) {
                    $user_id = auth('api')->user()->id;
                    $query
                        ->orWhere('procurement_plans.user_id_1', $user_id)
                        ->orWhere('procurement_plans.user_id_2', $user_id)
                        ->orWhere('procurement_plans.user_id_3', $user_id)
                        ->orWhere('procurement_plans.user_id_4', $user_id)
                        ->orWhere('procurement_plans.user_id_5', $user_id);
                })
                ->select(
                    "cpv_type",
                    DB::raw('sum(procurement_plans_details.count * procurement_plans_details.unit_amount) as total')
                )
                ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
                ->join('financial_classifier', 'financial_classifier.id', '=', 'procurement_plans_details.financial_classifier_id')
                ->where("procurement_plans_details.classifier_id",$classifier_id)
                ->where('financial_classifier_id',$financial_classifier)
                ->groupBy('cpv_type')
                ->get()->toArray();
	}


	function getProcurementByClassifierIdFinancialId(int $procurement_id,int $classifier_id,int $financial_classifier_id,int $cpv_type)
	{
	   //3-րդ view
        return $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
            ->where(function ($query) {
                $user_id = auth('api')->user()->id;
                $query
                    ->orWhere('procurement_plans.user_id_1', $user_id)
                    ->orWhere('procurement_plans.user_id_2', $user_id)
                    ->orWhere('procurement_plans.user_id_3', $user_id)
                    ->orWhere('procurement_plans.user_id_4', $user_id)
                    ->orWhere('procurement_plans.user_id_5', $user_id);
            })
              ->select(
                  "procurement_plans_details.id as id",
                  "procurement_plans.id as plan_row_id",
                  "cpv.id as cpv_id",
                  "cpv.name as cpv_name",
                  "cpv.code as cpv_code",
                  "procurement_plans.cpv_drop as cpv_drop",
                  "procurement_plans.cpv_type as cpv_type",
                  "procurement_plans.specifications_id as specifications_id",
                  "procurement_plans.specifications_id as specifications_id",
                  "procurement_plans.is_condition as is_condition",
                  "procurement_plans.condition_type as condition_type",
                  "procurement_plans.unit as unit",
                  "procurement_plans_details.count as count",
                  "procurement_plans_details.organize_count as organize_count",
                  "procurement_plans_details.date as date",
                  "procurement_plans_details.unit_amount as unit_amount",
                  "procurement_plans_details.type as type",
                  "procurement_plans_details.classifier_id as classifier_id",
                  "procurement_plans_details.financial_classifier_id as financial_classifier_id",
                  "procurement_plans.order_index as order_index",
                  "procurement_plans.status as status",
                  "procurement_plans.user_id_1 as user_id_1",
                  "procurement_plans.user_id_2 as user_id_2",
                  "procurement_plans.user_id_3 as user_id_3",
                  "procurement_plans.user_id_4 as user_id_4",
                  "procurement_plans.user_id_5 as user_id_5"
              )
              ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
              ->join('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')
              ->where('procurement_plans_details.classifier_id',$classifier_id)
              ->where('procurement_plans_details.financial_classifier_id',$financial_classifier_id)
              ->where("procurement_plans.cpv_type",$cpv_type)
              ->groupBy('procurement_plans_details.id')
              ->orderBy("cpv.code")
              ->paginate(10);
	}

	function getByCpvGroup(int $procurement_id,int $cpv_type)
	{
        $cpv_groups = $this->procurementPlan
                ->where("procurement_id",$procurement_id)->where("is_from_outside",0)
                ->where(function ($query) {
                    $user_id = auth('api')->user()->id;
                    $query
                        ->orWhere('procurement_plans.user_id_1', $user_id)
                        ->orWhere('procurement_plans.user_id_2', $user_id)
                        ->orWhere('procurement_plans.user_id_3', $user_id)
                        ->orWhere('procurement_plans.user_id_4', $user_id)
                        ->orWhere('procurement_plans.user_id_5', $user_id);
                })
                ->select(
                    \DB::raw('SUBSTR(cpv.code, 1, 3) as cpv_group'),
                     DB::raw('sum(procurement_plans_details.count * procurement_plans_details.unit_amount) as total')
                 )
                ->join('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')
                ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
                ->orderBy("cpv.id")
                ->groupBy("cpv_group")
                ->where("cpv_type",$cpv_type)
                ->orderBy("cpv.code")
                ->get();
        foreach ($cpv_groups as $cpv_group) {
            $cpv_code = $cpv_group['cpv_group'].'00000';
            $cpv = Cpv::where('code', $cpv_code)->first();
            if($cpv){
                $cpv_group['name'] = $cpv ? $cpv->name : '';
            } else {
                $cpv_code = $cpv_group['cpv_group'].'10000';
                $cpv = Cpv::where('code', $cpv_code)->first();
                $cpv_group['name'] = $cpv ? $cpv->name : '';
            }
        }
        return $cpv_groups;
	}
	function getListByCpvGroup(int $procurement_id,int $cpv_type,int $cpv_group)
	{
//	    return $this->procurementPlan->with('details') ->select(
//                     "*",
//                     "procurement_plans.id as id"
//             )
//             ->join('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')
//             ->where(\DB::raw('substr(cpv.code, 1, 3)'), '=', $cpv_group)
//             ->where("cpv_type",$cpv_type)
//             ->where("procurement_id",$procurement_id)
//             ->groupBy("procurement_plans.id")
//             ->paginate(10);

	    return $this->procurementPlan->where("procurement_id",$procurement_id)->where("is_from_outside",0)
                ->where(function ($query) {
                    $user_id = auth('api')->user()->id;
                    $query
                        ->orWhere('procurement_plans.user_id_1', $user_id)
                        ->orWhere('procurement_plans.user_id_2', $user_id)
                        ->orWhere('procurement_plans.user_id_3', $user_id)
                        ->orWhere('procurement_plans.user_id_4', $user_id)
                        ->orWhere('procurement_plans.user_id_5', $user_id);
                })
                ->select(
                  "procurement_plans_details.id as id",
                  "procurement_plans.id as plan_row_id",
                  "cpv.id as cpv_id",
                  "cpv.name as cpv_name",
                  "cpv.code as cpv_code",
                  "procurement_plans.cpv_drop as cpv_drop",
                  "procurement_plans.cpv_type as cpv_type",
                  "procurement_plans.specifications_id as specifications_id",
                  "procurement_plans.is_condition as is_condition",
                  "procurement_plans.condition_type as condition_type",
                  "procurement_plans.unit as unit",
                  "procurement_plans_details.count as count",
                  "procurement_plans_details.date as date",
                  "procurement_plans_details.unit_amount as unit_amount",
                  "procurement_plans_details.type as type",
                  "procurement_plans_details.organize_count as organize_count",
                  "procurement_plans_details.classifier_id as classifier_id",
                  "procurement_plans_details.financial_classifier_id as financial_classifier_id",
                  "procurement_plans_details.classifier as classifier",
                  "procurement_plans_details.financial_classifier as financial_classifier",
                  "procurement_plans_details.out_count as out_count",
                  "procurement_plans.order_index as order_index",
                  "procurement_plans.status as status",
                  "procurement_plans.user_id_1 as user_id_1",
                  "procurement_plans.user_id_2 as user_id_2",
                  "procurement_plans.user_id_3 as user_id_3",
                  "procurement_plans.user_id_4 as user_id_4",
                  "procurement_plans.user_id_5 as user_id_5"
              )
              ->join('procurement_plans_details', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
              ->join('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')
              ->where(\DB::raw('substr(cpv.code, 1, 3)'), '=', $cpv_group)
              ->where("procurement_plans.cpv_type",$cpv_type)
              ->groupBy('procurement_plans_details.id')
              ->orderBy("cpv.code")
              ->paginate(10);

	}
	function getPlanByOrganize(int $procurement_id,$type = false)
	{
	        $details =  ProcurementPlanDetails::select(
	                "procurement_plans_details.id as id",
	                "procurement_plans.procurement_id as p_id",
	                "procurement_plans.id as pl_id",
	                "procurement_plans.cpv_id as cpv_id",
	                "procurement_plans_details.id as pl_detail_id",
	                "procurement_plans_details.organize_count as organize_count",
	                "procurement_plans_details.type as details_type",
                    "cpv.name as cpv_name",
                    "cpv.code as cpv_code",
                    "procurement_plans.cpv_drop as cpv_drop"
                )
               ->join('procurement_plans', 'procurement_plans_details.procurement_plans_id', '=', 'procurement_plans.id')
               ->join('cpv', 'cpv.id', '=', 'procurement_plans.cpv_id')


	           ->where("procurement_plans.procurement_id",$procurement_id);
                if($type){
                  $details->where("procurement_plans_details.type",$type);
                }
                return $details->orderBy("cpv.code")->paginate(10);

	}

	function getHistoriesDetails(int $id)
	{
         return ProcurementPlanDetails::findOrFail($id)->histories()->orderBy("performed_at","desc")->select("meta","performed_at")->paginate(10);
    }

}