<?php

namespace App\Models\Organize;

use Spatie\Translatable\HasTranslations;
use App\Models\AbstractModel;
use App\Models\Participant\ParticipantRow;
use Illuminate\Support\Facades\Log;

class OrganizeRow extends AbstractModel{

    use HasTranslations;

    protected $table = 'organize_row';

    public $translatable = ['shipping_address'];

    protected $allowed = [
        'count',
        'supply',
        'supply_date',
        'shipping_address',
        'is_main_tool',
        'is_collateral_requirement',
        'plan_specifications',
        'is_product_info',
        'procurementPlan',
        'classifier_id',
        'financial_classifier_id',
        'classifier',
        'financial_classifier',
        'user_id_1',
        'user_id_2',
        'user_id_3',
        'user_id_4',
        'user_id_5',
        'participantsList',
        'unit',
        'organizeRowPercent',
        'is_from_outside',
        'winner_lot_trademark',
        'winner_lot_brand',
        'winner_lot_manufacturer',
        'winner_lot_specification',
        'view_id',
        'details_id',
        'unit_amount',
        'type',
        'is_condition',
        'condition_type',
        'done_negotiations',
        'set_completed',
        'plan_row_id',
        'cpv_id',
        'cpv_name',
        'cpv_code',
        'cpv_drop',
        'cpv_type',
    ];
    protected $default = [
        'count',
        'supply',
        'supply_date',
        'shipping_address',
        'is_main_tool',
        'is_collateral_requirement',
        'plan_specifications',
        'is_product_info',
        'procurementPlan',
        'classifier_id',
        'financial_classifier_id',
        'user_id_1',
        'user_id_2',
        'user_id_3',
        'user_id_4',
        'user_id_5',
        'participantsList',
        'unit',
        'organizeRowPercent',
        'is_from_outside',
        'winner_lot_trademark',
        'winner_lot_brand',
        'winner_lot_manufacturer',
        'winner_lot_specification',
        'view_id',
        'details_id',
        'unit_amount',
        'type',
        'is_condition',
        'condition_type',
        'done_negotiations',
        'set_completed',
        'plan_row_id',
        'cpv_id',
        'cpv_name',
        'cpv_drop',
        'cpv_type',
        'cpv_code',
    ];

    public function organize(){
        return $this->hasOne('App\Models\Organize\Organize', 'id', 'organize_id')->with('user');
    }
    public function organizeOnePerson(){
        return $this->hasOne('App\Models\Organize\OrganizeOnePerson', 'id', 'organize_id');
    }
    public function organizeItender(){
        return $this->hasOne('App\Models\Organize\OrganizeItender', 'id', 'organize_id');
    }
    public function participants(){
        return $this->hasMany('App\Models\Participant\ParticipantRow', 'organize_row_id', 'id')->with('info')->with('userInfo')->with('row')->orderBy('value');
    }
    public function participantsOrderByValue(){
        return $this->hasMany('App\Models\Participant\ParticipantRow', 'organize_row_id', 'id')
            ->where('is_satisfactory', 1)
            ->with('info')->with('userInfo')->orderBy('value');
    }
    public function participantsOrderByCost(){
        return $this->hasMany('App\Models\Participant\ParticipantRow', 'organize_row_id', 'id')
            ->where('is_satisfactory', 1)
            ->with('info')->with('userInfo')->orderBy('cost');
    }
    public function participantsGroups(){
        return $this->hasMany('App\Models\Participant\ParticipantRow', 'organize_row_id', 'id')->with('info')->with('userInfo')->orderBy('value');
    }
    public function offer(){
        return $this->hasOne('App\Models\Participant\ParticipantRow', 'id', 'won_lot_id');
    }
    public function winner(){
        return $this->hasOne('App\Models\Participant\ParticipantGroup', 'id', 'winner_participant_id')->with('lots')->with('participant');
    }
    public function winnerUser(){
        return $this->hasOne('App\Models\User\User', 'id', 'winner_user_id')->with('lots')->with('organisation');
    }
    public function organizeRowPercent(){
        return $this->hasOne('App\Models\Organize\OrganizeRowPercent', 'organize_row_id', 'id');
    }
    public function cpv(){
        return $this->hasOneThrough(
            'App\Models\Cpv\Cpv',
            'App\Models\Procurement\ProcurementPlan',
            'id',
            'id',
            'procurement_plan_id',
            'cpv_id'
        )->with('participants');
    }
    public function procurementPlan(){
        return $this->hasOne('App\Models\Procurement\ProcurementPlan', 'id', 'procurement_plan_id')
            ->with('details')
            ->with('cpv')
            ->with('cpvOutside')
            ->with('specifications')
            ->with('procurement');
    }
    public function participantsList()
    {
        $lots = ParticipantRow::where("organize_row_id", $this->id)->where("is_satisfactory", 1)->where("canceled_contract_request", 0)
        ->select(
            "participant_data.*",
            "participant_data_row.id as participant_data_row_id",
            "participant_data_row.cost as cost",
            "participant_data_row.vat as vat",
            "participant_data_row.is_satisfactory as is_satisfactory",
            "participant_data_row.value as price",
            "participant_data_row.new_value as new_price",
            "participant_data_row.organize_row_id as organize_row_id",
            "participant_data.group_id as participant_group_id",
            "procurement_plans.is_condition as is_condition",
            "procurement_plans.condition_type as condition_type",
            "procurement_plans_details.unit_amount as plan_unit_price",
            "organize_row.count as orgranize_row_count",
        )
        ->join('participant_data', 'participant_data.group_id', '=', 'participant_data_row.row_group_id')
        ->join('organize_row', 'organize_row.id', '=', 'participant_data_row.organize_row_id')
        ->join('procurement_plans', 'procurement_plans.id', '=', 'organize_row.procurement_plan_id')
        ->join('procurement_plans_details', 'procurement_plans_details.id', '=', 'organize_row.plan_details_id')
        ->groupBy('participant_data_row.row_group_id')
        ->orderBy('cost')
        ->get();

        if(count($lots)!==0){

            foreach ($lots as $lot) {
                $lot->name = json_decode($lot->name);
            }

            foreach ($lots as $lot) {
                $lot->current_vat = 0;
                $lot->current_price = 0;
                if($lot->new_price){
                    $lot->current_vat = $lot->vat ? $lot->new_price * 0.2 : 0;
                    $lot->current_price = $lot->new_price;
                } else {
                    $lot->current_vat = $lot->vat;
                    $lot->current_price = $lot->price;
                }
                $lot->current_price_vat = $lot->current_price + $lot->current_vat;
            }

            foreach ($lots as $lot) {
                $lot->estimated_price = $lot->plan_unit_price * $lot->orgranize_row_count;
            }

            // ՆՎԱԶԱԳՈՒՅՆ ԳՆԱՅԻՆ ԱՌԱՋԱՐԿԻ ՈՐՈՇՈՒՄ -------------------------------------------------------------------------------------------

            $min_price = $lots[0]->current_price;

            foreach ($lots as $lot) {
                if($lot->current_price < $min_price){
                    $min_price = $lot->current_price;
                }
            }

            // -----------------------------------------------------------------------------------------------------------

            // ՀԱՂԹՈՂ/ՆԵՐԻ ՈՐՈՇՈՒՄ -------------------------------------------------------------------------------------------
            foreach ($lots as $lot) {
                if($lot->current_price === $min_price){
                    $lot->winner = true;
                } else {
                    $lot->winner = false;
                }
            }
            
            $winners_count = false;

            foreach ($lots as $lot) {
                if($lot->winner){
                    $winners_count += 1;
                }
            }

            // -----------------------------------------------------------------------------------------------------------

            // ՆՎԱԶԱԳՈՒՅՆ ԳՆԵՐԻ ԱՐԺԵՔՆԵՐԻ ՀԱՎԱՍԱՐՈՒԹՅՈՒՆ
            if($winners_count > 1){
                return $lots; 
            }
            // ------------------------------------------

            foreach ($lots as $lot) {
                if($lot->current_price_vat <= $lot->estimated_price){
                    $lot->enough = true;
                } else if($this->set_completed && $lot->winner) {
                    $lot->enough = true;
                } else {
                    $lot->winner = false;
                    $lot->enough = false;
                }
            }

            $winners_count = false;

            foreach ($lots as $lot) {
                if($lot->winner){
                    $winners_count += 1;
                }
            }

            if($winners_count === 1){
                return $lots; 
            } else {
                $min_price = $lots[0]->current_price;
    
                foreach ($lots as $lot) {
                    if($lot->enough){
                        $min_price = $lot->current_price;
                    }
                }
    
                foreach ($lots as $lot) {
                    if($lot->enough){
                        if($lot->current_price === $min_price){
                            $lot->winner = true;
                        } else {
                            $lot->winner = false;
                        }
                    }
                }

                foreach ($lots as $lot) {
                    if($lot->current_price <= $lot->estimated_price){
                        $lot->enough = true;
                    } else if($this->set_completed && $lot->winner) {
                        $lot->enough = true;
                    } else {
                        $lot->winner = false;
                        $lot->enough = false;
                    }
                }
    
                return $lots->toArray();
            }

            return usort($lots,function($first,$second){
                return $first->current_price_vat > $second->current_price_vat;
            });
        }
        return [];
    }

}
