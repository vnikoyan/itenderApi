<?php


namespace App\Models\Participant;


use App\Models\AbstractModel;
use Panoscape\History\HasHistories;

class ParticipantRow extends AbstractModel{

    use HasHistories;

    protected $table = 'participant_data_row';
    protected $allowed = ["row_group_id","organize_row_id","cost","profit","value","new_value","vat"];
    protected $default = ["row_group_id","organize_row_id","cost","profit","value","new_value","vat"];
    protected $fillable = ["row_group_id","organize_row_id","cost","profit","value","new_value","vat"];


    public function getModelLabel()
    {
        // TODO: Implement getModelLabel() method.
        return $this->display_name;
    }

    public function userInfo(){
        return $this->hasOne('App\Models\User\User', 'id', 'participant_id')->with('wonLots')->with('organisation');
    }

    public function group(){
        return $this->hasOne('App\Models\Participant\ParticipantGroup', 'id', 'row_group_id')->with('lots')->with('participant');
    }

    public function row(){
        return $this->hasOne('App\Models\Organize\OrganizeRow', 'id', 'organize_row_id')->with('procurementPlan');
    }

    public function priceWord($price){
        $f = new \NumberFormatter("hy", \NumberFormatter::SPELLOUT);
        $price_word = $f->format($price);
        return $price_word;
    }

    // public function userInfo(){
    //     return $this->hasOneThrough(
    //         'App\Models\User\User',
    //         'App\Models\Participant\ParticipantGroup',
    //         'id',
    //         'id',
    //         'row_group_id',
    //         'user_id'
    //     );
    // }

    public function info(){
        return $this->hasOneThrough(
            'App\Models\Participant\Participant',
            'App\Models\Participant\ParticipantGroup',
            'id',
            'group_id',
            'row_group_id',
            'id'
        );
    }

    public static function getWinner($organize_row_id)
    {
        $instance = new static;

        $lots = $instance->where("organize_row_id", $organize_row_id)->where("is_satisfactory", 1)->where("canceled_contract_request", 0)
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
                } else {
                    $lot->winner = false;
                    $lot->enough = false;
                }
            }
            

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


            // foreach ($lots as $lot) {
            //     $arjeq = $lot->new_price;
            //     if($lot->new_price){
            //         $arjeq = $lot->new_price;
            //     } else {
            //         $arjeq = $lot->price;
            //     }
            //     return $arjeq;
            //     $current_cost = $lot->cost;
            //     if($current_price == 0){
            //         $current_price = $lot->clean_price;
            //     } else {
            //         $current_cost = ((20 / 100) * $current_price) + $current_price;
            //     }
            //     $lot->cost = $current_price;
            //     $lot->unit_price_clean = $current_price / $lot->orgranize_row_count;
            //     $lot->unit_price_vat = $current_cost / $lot->orgranize_row_count;
            //     $lot->name = json_decode($lot->name);
            //     $lot->address = json_decode($lot->address);
            //     if($lot->unit_price_vat <= $lot->plan_unit_price){
            //         $lot->enough = true;
            //     } else {
            //         $lot->enough = false;
            //     }
            // }
            // $winner = $lots[0];
            // $is_some_enough = false;

            // foreach ($lots as $lot) {
            //     if($lot->enough === true){
            //         $is_some_enough = true;
            //         break;
            //     }
            // }
            // if($is_some_enough == true){
            //     foreach ($lots as $lot) {
            //         if($lot->enough===true){
            //             if($lot->unit_price_clean <= $winner->unit_price_clean){
            //                 $winner = $lot;
            //             }
            //         }else {
            //             $lot->winner = false;
            //         }
            //     }
            //     foreach ($lots as $lot) {
            //         if($lot->unit_price_clean == $winner->unit_price_clean){
            //             $lot->winner = true;
            //         }else {
            //             $lot->winner = false;
            //         }
            //     }
            //     return $lots;
            // } else {
            //     return $lots;
            // }

            return $lots;

        }
        return [];
    }
}
