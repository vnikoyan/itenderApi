<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Models\AbstractModel;

class Organisation extends AbstractModel
{
    use HasTranslations,SoftDeletes,Notifiable;

    protected $table = 'users_state_organisation';

    public $translatable = ['name',"address","company_type","nickname","region","city","address","bank_name","director_name","director_position"];
    public $allowed      = [
        'name',
        'translations',
        "bank_account",
        "balans",
        "tin",
        "bank_name",
        "phone",
        "passport_serial_number",
        "passport_given_at",
        "passport_from",
        "passport_valid_until",
        "id_card_number"
    ];
    public $default      = [
        'name',
        'translations',
        "bank_account",
        "balans",
        "tin",
        "bank_name",
        "phone",
        "passport_serial_number",
        "passport_given_at",
        "passport_from",
        "passport_valid_until",
        "id_card_number"
    ];


    // php artisan make:migration create_user_contrat_table

    public function user(){
        return $this->hasMany('App\Models\User\User', 'parent_id', 'id')->groupBy('divisions')->orderBy('divisions',
        "desc");
    }

    public function userNoG(){
        return $this->hasMany('App\Models\User\User', 'parent_id', 'id')->orderBy('divisions',
        "desc");
    }

    public function contrat()
    {
        return $this->hasOne('App\Models\User\Contrat',"user_id","id");
    }

}
