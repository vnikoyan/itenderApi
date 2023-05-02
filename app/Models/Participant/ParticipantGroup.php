<?php

namespace App\Models\Participant;

use App\Models\AbstractModel;
use Spatie\Translatable\HasTranslations;

class ParticipantGroup extends AbstractModel
{
    use HasTranslations;

    protected $table = 'participant_groups';

    protected $allowed = [ "is_cooperation","is_agency_agreement", "organize_id"];
    protected $default = [ "is_cooperation","is_agency_agreement", "organize_id"];


    public function lots(){
        return $this->hasMany('App\Models\Participant\ParticipantRow', 'row_group_id', 'id')->with('row');
    }

    public function rows(){
        return $this->hasManyThrough(
            'App\Models\Organize\OrganizeRow',
            'App\Models\Participant\ParticipantRow',
            'row_group_id',
            'id',
            'id',
            'organize_row_id'
        )->with('procurementPlan');
    }

    public function wonLots(){
        return $this->hasMany('App\Models\Organize\OrganizeRow', 'winner_participant_id', 'id')->with('procurementPlan')->with('organizeRowPercent')->with('winner')->with('offer');
    }

    public function participant(){
        return $this->hasMany('App\Models\Participant\Participant', 'group_id', 'id');
    }

    public function participantUser(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id')->with('organisation')->with('suggestions');
    }

}
