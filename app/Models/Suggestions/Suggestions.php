<?php

namespace App\Models\Suggestions;

use App\Models\AbstractModel;

class Suggestions extends AbstractModel
{
    protected $table = 'suggestions';

    protected $allowed = ["responded", "organize", "organize_rows"];
    protected $default = ["responded", "organize", "organize_rows"];

    public function organize(){
        return $this->hasOne('App\Models\Organize\OrganizeOnePerson', 'id', 'organize_id')->with('organizeRows');
    }

    public function organizeItender(){
        return $this->hasOne('App\Models\Organize\OrganizeItender', 'id', 'organize_id')->with('organizeRows');
    }

    public function provider(){
        return $this->hasOne('App\Models\User\User', 'id', 'provider_id')->with('organisation');
    }

    public function client(){
        return $this->hasOne('App\Models\User\User', 'id', 'client_id')->with('organisation');
    }

    public function favorite(){
        return $this->hasMany('App\Models\Suggestions\FavoriteSuggestions', 'suggestion_id', 'id');
    }

    public function isFavorite(){
        $is_favorite = FavoriteSuggestions::where([['user_id', auth('api')->user()->id], ['suggestion_id',$this->id]])->first();
        return boolval($is_favorite);
    }
}
