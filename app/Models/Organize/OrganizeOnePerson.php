<?php

namespace App\Models\Organize;

use Spatie\Translatable\HasTranslations;
use App\Models\AbstractModel;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Participant\ParticipantRow;
use App\Models\User\User;
use App\Models\Organize\OrganizeRow;
use App\Models\Participant\ParticipantGroup;
use App\Http\Resources\Organize\OnePerson\OrganizeParticipantResource;
use App\Http\Resources\Organize\OnePerson\OrganizeWinnerParticipantResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrganizeOnePerson extends AbstractModel{

    use HasTranslations;

    protected $table = 'organize';

    public $translatable = ['evaluator_secretary_name','evaluator_president','evaluator_president'];

    protected $fillable = ['create_contract'];

    protected $allowed = [
        'procurement',
        'participants',
        'organize_rows',
        'translations',

        'name',
        'name_ru',
        'code',
        'code_ru',
        'shipping_address',
        'other_requirements',
        'calendar_schedule',
        'publication',
        'contract_html_hy',

        'opening_date_time',

        'winner_by_lots',
        'send_to_all_participants',
        'publicize',
        'confirm',
        'create_contract',

        'decision_number',
        'purchase_schedule',
        'protocols_copy_number',
        'protocol_presentation_deadline',
        'work_type',
        'least_work_percent',
        'cpv_type',
        'is_with_condition',
        'is_construction',

        'winner_user_price_word',
        'winner_user_price',
        'winners',
        'suggestions',
        'suggestions_responded',
        'suggestions_count',
        'suggestions_responded_count',
        'lots'
    ];
    protected $default = [
        'procurement',
        'participants',
        'organize_rows',
        'translations',

        'name',
        'name_ru',
        'code',
        'code_ru',
        'shipping_address',
        'other_requirements',
        'calendar_schedule',
        'publication',
        'contract_html_hy',

        'opening_date_time',

        'winner_by_lots',
        'send_to_all_participants',
        'publicize',
        'confirm',
        'create_contract',

        'decision_number',
        'purchase_schedule',
        'protocols_copy_number',
        'protocol_presentation_deadline',
        'work_type',
        'least_work_percent',
        'cpv_type',
        'is_with_condition',
        'is_construction',

        'winner_user_price_word',
        'winner_user_price',
        'winners',
        'suggestions',
        'suggestions_responded',
        'suggestions_count',
        'suggestions_responded_count',
        'lots'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('is_one_person', function(Builder $builder) {
            $builder->where([['is_one_person', '=', 1], ['is_itender', '=', 0]]);
        });
    }

    public function priceWord($price){
        $f = new \NumberFormatter("hy", \NumberFormatter::SPELLOUT);
        $price_word = $f->format($price);
        return $price_word;
    }

    public static function finish_date($id){
        $organize = OrganizeOnePerson::find($id);
        return $organize->opening_date_time;
    }

    public function status(){
        if(strtotime($this->opening_date_time) < strtotime(date('Y-m-d H:i:s'))){
            return 'finished';
        } else {
            return 'active';
        }
    }

    public function participants(){
        $participants = [];
        $participants_list = [];
        foreach ($this->organizeRows as $row) {
            foreach ($row->participants as $participant_row) {
                $participants[] = $participant_row->group;
            }
        }
        $participants = array_unique($participants);
        foreach ($participants as $participant) {
            if($participant){
                $participants_list[] = new OrganizeParticipantResource($participant);
            }
        }
        return $participants_list;
    }

    public function winners(){
        $winners = [];
        if($this->winner_by_lots){
            foreach ($this->organizeRows as $row) {
                $winner_participant = ParticipantGroup::with('lots')->with('participant')->find($row->winner_participant_id);
                $winners[] = $winner_participant;
            }
        } else {
            $winner_participant = ParticipantGroup::with('lots')->with('participant')->find($this->winner_participant_id);
            $winners[] = $winner_participant;
        }
        foreach ($winners as $winner) {
            if($winner){
                foreach ($winner->wonLots as $key => $won_lot) {
                    if($won_lot->organize_id !== $this->id){
                        unset($winner->wonLots[$key]);
                    }
                }
            }
        }
        $winners = array_unique($winners);

        $winnerArray = [];

        foreach ($winners as $winner) {
            if($winner){
                if(isset($winner['organize_id'])){
                    $winnerArray[] = new OrganizeWinnerParticipantResource($winner);
                }
            }
        }
        return $winnerArray;
    }

    public function lots()
    {
        return $this->hasManyThrough(
            'App\Models\Participant\ParticipantRow',
            'App\Models\Organize\OrganizeRow',
            'organize_id',
            'organize_row_id',
            'id',
            'id'
        );
    }

    public function tender(){
        return $this->hasOne('App\Models\Tender\TenderState', 'one_person_organize_id', 'id');
    }

    public function procurement(){
        return $this->hasOne('App\Models\Procurement\Procurement', 'id', 'procurement_id');
    }

    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }

    public function organizeRows(){
        return $this->hasMany('App\Models\Organize\OrganizeRow', 'organize_id', 'id')->with('procurementPlan')->with('participants')->with('winnerUser')->with('organizeRowPercent')->with("cpv");
    }

    public function suggestions(){
        return $this->hasMany('App\Models\Suggestions\Suggestions', 'organize_id', 'id');
    }

    public function suggestionsResponded(){
        return $this->hasMany('App\Models\Suggestions\Suggestions', 'organize_id', 'id')->where('responded', 1);
    }

    // public function participants()
    // {
    //     return $this->hasManyThrough(
    //         'App\Models\Participant\ParticipantRow',
    //         'App\Models\Organize\OrganizeRow',
    //         'organize_id',
    //         'organize_row_id',
    //         'id',
    //         'id'
    //     );
    // }
}
