<?php

namespace App\Models\Statistics;

use Illuminate\Database\Eloquent\Model;

class CpvStatistics extends Model
{
    public function participants(){
        return $this->hasMany('App\Models\Statistics\CpvStatisticsParticipants', 'cpv_statistics_id', 'id');
    }
}
