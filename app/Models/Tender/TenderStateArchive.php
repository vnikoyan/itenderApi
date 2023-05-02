<?php

namespace App\Models\Tender;
use App\Models\UserCategories\UserCpvs;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;

class TenderStateArchive extends Model{

    use HasTranslations;

    protected $table = 'tender_state_archive';
    
    public $translatable = ['title',"link"];

    /**
     * Encode the given value as JSON.
     *
     * @param  mixed  $value
     * @return string
     */
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function isFavorite(){
        $is_favorite = FavoriteTenderState::where([['favorite_tender_states.user_id', auth('api')->user()->id],
        ['favorite_tender_states.tender_state_id',$this->id]])->first();
        return boolval($is_favorite);
    }

    public function isMine(){
        if(auth('api')->user() && ($this->organizeItender) && (($this->organizeItender->user_id === auth('api')->user()->id))){
            return true;
        } else {
            return false;
        }
    }

    public function isViewed(){
        $is_viewed = UserTenders::where([['user_tenders.user_id', auth('api')->user()->id],['user_tenders.tender_state_id',$this->id]])->first();
        return boolval($is_viewed);
    }

    public function getCpvType(){
        $cpvs = $this->getCpvSingle;
        if($cpvs && $cpvs->cpv){
            switch ($cpvs->cpv->type) {
                case '1':
                    return 'product';
                case '2':
                    return 'service';
                case '3':
                    return 'work';
                default:
                    return 'false';
            }
        } else{
            return 'product';
        }
    }

    public function getCpvForUsers(){
        $user_id = auth('api')->user()->id;
        $cpvs = $this->getCpvDemo;
        // foreach($cpvs as $cpv){
        //     $checkUserCpvs = UserCpvs::where("cpv_id",$cpv->cpv_id)->where("user_id",$user_id)->first();
        //     (is_null($checkUserCpvs)) ?  $cpv->is_mine = false : $cpv->is_mine = true;
        // }
        return  $cpvs;
    }

    public function isEndedElectronicLink(){
        $date = new DateTime($this->end_date);

        $date->modify('+1 day');    

        $after_finish_date = $date->format("Y-m-d H:i:s");

        if($after_finish_date < date("Y-m-d H:i:s")){
            if($this->type === 'ELECTRONIC'){
                return 'https://armeps.am/ppcm/public/bid-report';
            } elseif($this->type === 'ELECTRONIC AUCTION'){
                return 'https://eauction.armeps.am/hy/public/tender/';
            }
        }
        return false;
    }
    

    public function getCpv(){
        return $this->hasMany('App\Models\Tender\TenderStateCpv', 'tender_state_id', 'id')->with('cpv');
    }

    public function getCpvSingle(){
        return $this->hasOne('App\Models\Tender\TenderStateCpv', 'tender_state_id', 'id')->with('cpv');
    }

    public function getCpvDemo(){
        return $this->hasMany('App\Models\Tender\TenderStateCpv', 'tender_state_id', 'id')->limit(3);
    }

    public function getCategory(){
        return $this->hasMany('App\Models\Tender\TenderStateCategory', 'tender_state_id', 'id')->with('category');
    }

    public function announcements(){
        return $this->hasMany('App\Models\Tender\TenderState', 'tender_state_id', 'id');
    }

    public function tenderAnnouncements(){
        return $this->hasMany('App\Models\Tender\TenderState', 'tender_state_id', 'tender_state_id');
    }
    
    public function favorite(){
        return $this->hasMany('App\Models\Tender\FavoriteTenderState', 'tender_state_id', 'id');
    }

    public function region(){
        return $this->hasOne('App\Models\Settings\Region', 'id', 'regions');
    }

    public function organizator(){
        return $this->hasOne('App\Models\Tender\Organizator', 'id', 'organizer_id');
    }

    public function stateMinistry(){
        return $this->hasOne('App\Models\Settings\Ministry', 'id', 'ministry');
    }

    public function stateInstitution(){
        return $this->hasOne('App\Models\Settings\StateInstitution', 'id', 'state_institution');
    }

    public function organizeOnePerson(){
        return $this->hasOne('App\Models\Organize\OrganizeOnePerson', 'id', 'one_person_organize_id');
    }

    public function organizeItender(){
        return $this->hasOne('App\Models\Organize\OrganizeItender', 'id', 'one_person_organize_id');
    }

    public function procedure(){
        return $this->hasOne('App\Models\Tender\Procedure', 'id', 'procedure_type');
    }
    
}