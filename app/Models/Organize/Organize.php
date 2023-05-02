<?php

namespace App\Models\Organize;

use Spatie\Translatable\HasTranslations;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Organize extends AbstractModel{

    use HasTranslations;

    protected $table = 'organize';

    public $translatable = ['name','code','evaluator_secretary_name','evaluator_president','evaluator_president', 'evaluator_secretary_position'];

    protected $allowed = [
        'procurement',
        'participants',
        'organize_rows',
        'translations',
        "evaluator_secretary_name",
        "evaluator_secretary_position",
        "evaluator_secretary_email",
        "evaluator_secretary_phone",
        "evaluator_member",
        'rows_total_price',
        'organize_type',
        'text_approval_date',
        'decision_number',
        'public_date',
        'submission_date',
        'opening_date',
        'opening_time',
        'prepayment',
        'prepayment_max',
        'prepayment_max_text',
        'prepayment_time',
        'confirm',
        'cpv_type',
        'publication',
        'contract_html_hy',
        'contract_html_ru',
        'rights_responsibilities_fulfillment',
        'create_contract',
        'done_negotiations',
        'get_evaluation_session',
        'get_invitation',
        'paper_fee',
        'fee',
        'account_number',
        'is_with_condition',
        'is_construction',
        'calendar_schedule',
        'least_work_percent',
        'protocols_copy_number',
        'protocol_presentation_deadline',
        'locale_negotiations',
        'is_correction',
        'is_negotiations',
        'repair_services',
        'is_with_specification'
    ];
    protected $default = [
        'procurement',
        'participants',
        'organize_rows',
        'translations',

        "evaluator_secretary_name",
        "evaluator_secretary_position",
        "evaluator_secretary_email",
        "evaluator_secretary_phone",
        "evaluator_member",

        'confirm',
        'cpv_type',
        'publication',
        'contract_html_hy',
        'contract_html_ru',
        'rights_responsibilities_fulfillment',
        'create_contract',
        'done_negotiations',
        'get_evaluation_session',
        'get_invitation',
        'organize_type',
        'text_approval_date',
        'decision_number',
        'public_date',
        'submission_date',
        'opening_date',
        'opening_time',
        'prepayment',
        'prepayment_max',
        'prepayment_max_text',
        'prepayment_time',
        'is_construction',
        'is_with_condition',
        'rows_total_price',
        'calendar_schedule',
        'least_work_percent',
        'protocols_copy_number',
        'protocol_presentation_deadline',

        'paper_fee',
        'fee',
        'account_number',
        'locale_negotiations',
        'is_correction',
        'is_negotiations',
        'repair_services',
        'is_with_specification'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('is_competitive', function(Builder $builder) {
            $builder->where([['is_one_person', '=', 0], ['is_itender', '=', 0]]);
        });
    }

    public static function finish_date($id){
        $organize = Organize::find($id);
        $date = Carbon::createFromFormat('Y-m-d', $organize->opening_date);
        $finish_date = $date->addDays($organize->submission_date);
        return $finish_date->format('Y-m-d');
    }

    public function priceWord($price){
        $f = new \NumberFormatter("hy", \NumberFormatter::SPELLOUT);
        $price_word = $f->format($price);
        return $price_word;
    }

    public function rowsTotalPrice(){
        $total_price = 0;
        foreach ($this->organizeRows as $row) {
            $count = $row->count;
            $price = $row->procurementPlan->details[0]->unit_amount;
            $total_price+=$price*$count;
        }
        return $total_price;
    }

    public function status(){
        if(strtotime($this->opening_date_time) < strtotime(date('Y-m-d H:i:s'))){
            return 'finished';
        } else {
            return 'active';
        }
    }

    public function procurement(){
        return $this->hasOne('App\Models\Procurement\Procurement', 'id', 'procurement_id');
    }

    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }

    public function organizeRows(){
        return $this->hasMany('App\Models\Organize\OrganizeRow', 'organize_id', 'id')->with('organize')->orderBy('view_id')->with('organizeRowPercent')->with('procurementPlan');
    }

    public function participants(){
        return $this->hasMany('App\Models\Participant\ParticipantGroup', 'organize_id', 'id')->with('lots')->with('participant');
    }

    public function organizeRowPercents(){
        return $this->hasMany('App\Models\Organize\OrganizeRow', 'organize_id', 'id')
            ->with("organizeRowPercent")->with("procurementPlan");
    }

//    public function organize(){ organize
//        return $this->hasMany('App\Models\Organize\Organize', 'procurement_id', 'id');
//    }


}
