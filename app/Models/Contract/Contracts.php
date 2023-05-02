<?php

namespace App\Models\Contract;

use App\Models\AbstractModel;

class Contracts extends AbstractModel
{
    protected $table = 'contracts';

    protected $allowed = ["code", "sign_date", "name", "lots", "organize", "participant"];
    protected $default = ["code", "sign_date", "name", "lots", "organize", "participant"];

    public function lots(){
        return $this->hasMany('App\Models\Contract\ContractLots', 'contract_id', 'id')->with('organizeRow');
    }

    public function organize(){
        return $this->hasOne('App\Models\Organize\Organize', 'id', 'organize_id')->with('organizeRows');
    }

    public function organizeOnePerson(){
        return $this->hasOne('App\Models\Organize\OrganizeOnePerson', 'id', 'organize_id')->with('organizeRows');
    }

    public function organizeItender(){
        return $this->hasOne('App\Models\Organize\OrganizeItender', 'id', 'organize_id')->with('organizeRows');
    }

    public function organizeData(){
        $organize = $this->organizeOnePerson;
        if($organize){
            return $organize;
        } else {
            $organize = $this->organizeItender;
            if($organize){
                return $organize;
            } else {
                return $this->organize;
            }
        }
    }

    public function organizeType(){
        $organize = $this->organizeOnePerson;
        if($organize){
            return 'one_person';
        } else {
            $organize = $this->organizeItender;
            if($organize){
                return 'itender';
            } else {
                return 'competitive';
            }
        }
    }

    public function participant(){
        return $this->hasOne('App\Models\Participant\ParticipantGroup', 'id', 'provider_id')->with('lots')->with('participant');
    }

    public function participantUsers(){
        return $this->hasOne('App\Models\User\User', 'id', 'provider_user_id')->with('lots');
    }

    public function clientUser(){
        return $this->hasOne('App\Models\User\User', 'id', 'client_id')->with('lots');
    }

    public function client(){
        return $this->hasOne('App\Models\Contract\ContractClient', 'id', 'contract_client_id');
    }

    public function orders(){
        return $this->hasMany('App\Models\Contract\ContractOrders', 'contract_id', 'id');
    }
}
