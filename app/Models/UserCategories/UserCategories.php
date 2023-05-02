<?php

namespace App\Models\UserCategories;

use Illuminate\Database\Eloquent\Model;

class UserCategories extends Model
{
    protected $table = 'user_categories';

    public function user(){
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }

    public function category(){
        return $this->hasOne('App\Models\Categories\Categories', 'id', 'category_id');
    }
}
