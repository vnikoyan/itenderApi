<?php
namespace App\Models\User;

use DB;
use Mail;
use JWTAuth;
use Carbon\Carbon;
use App\Models\AbstractModel;
use App\Models\Cpv\Cpv;
use App\Models\Order\OrderState;
use App\Models\UserCategories\UserCpvs;
use Illuminate\Notifications\Notifiable;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Translatable\HasTranslations;


class User extends AbstractModel implements JWTSubject
{
    use HasTranslations,Notifiable,SoftDeletes;


    public $translatable = ['name',"company_type","nickname","region","city","address","bank_name","director_name"];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Default fields returned.
     *
     * @var array
     */
    protected $default = [
        'email',
        'phone',
        'tin',
        'bank_account',
        "organisation",
        "divisions",
        "name",
        "name_ru",
        "members",
        'vat_payer_type',
        'rights_responsibilities_fulfillment',
        'unseen_suggestions',
        'password',
        'package',
        'username',
        'probation',
        'email_notifications_time',
        'email_notifications',
        'telegram_notifications',
        'telegram_id',
        'is_manager',
        'position'
    ];

    /**
     * Allowed fields in response.
     *
     * @var array
     */
    protected $allowed = [
        'email',
        'divisions',
        "organisation",
        'name',
        "name_ru",
        "members",
        "phone",
        'vat_payer_type',
        'rights_responsibilities_fulfillment',
        'unseen_suggestions',
        'password',
        'package',
        'username',
        'probation',
        'email_notifications_time',
        'email_notifications',
        'telegram_notifications',
        'telegram_id',
        'is_manager',
        'position'
    ];

    protected $fillable = [
        'email','translations'
    ];

    /**
     * Map any aliased parameters.
     *
     * @var array
     */
    protected $dependant = [
        'feedback_count'    => false,
    ];
    public function setDefault(array $default){
        $this->default = $default;
    }

    public function setAllowed(array $allowed){
        $this->allowed = $allowed;
    }

    public function setFillable(array $fillable)
    {
         $this->fillable = $fillable;
    }


    /**
     * Aliases for the requested resource. Used to identify the fields requested.
     *
     * @var array
     */
    protected $aliases = ['user'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Return the user.
     */
    public function user()
    {
        return $this;
    }

    /**
     * Get the id of the current user.
     */
    public function id()
    {
        if(!empty($this->toArray())){
            return $this->id;
        }
        return false;
    }



    public  function getAuthIdentifierName()
    {
        return "id";
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    /**
     * Create a new user based on the supplied information.
     *
     * @param   array $data
     * @return  User
     */
    public static function build($data)
    {

        $users_state_organisation  = new Organisation();
        $users_state_organisation->tin = (string) $data['tin'];
        $users_state_organisation->phone = (string) $data['phone'];
        $users_state_organisation->bank_account = (string) $data['bank_account'];
        $users_state_organisation->id_card_number = (string) $data['id_card_number'];
        $users_state_organisation->passport_serial_number = (string) $data['passport']['serial_number'];
        $users_state_organisation->passport_given_at = (string) $data['passport']['given_at'];
        $users_state_organisation->passport_from = (string) $data['passport']['from'];
        $users_state_organisation->passport_valid_until = (string) $data['passport']['valid_until'];

        foreach($data['name'] as $key => $value){
            $users_state_organisation->setTranslation('name', $key , $data['name'][$key]);
            $users_state_organisation->setTranslation('company_type', $key , $data['company_type'][$key]);
            $users_state_organisation->setTranslation('region', $key , $data['region'][$key]);
            $users_state_organisation->setTranslation('city', $key , $data['city'][$key]);
            $users_state_organisation->setTranslation('address', $key , $data['address'][$key]);
            $users_state_organisation->setTranslation('bank_name', $key , $data['bank_name'][$key]);
            $users_state_organisation->setTranslation('director_name', $key , $data['director_name'][$key]);
            $users_state_organisation->setTranslation('director_position', $key , $data['director_position'][$key]);
        }

        $users_state_organisation->save();
        // divisions
        $user                   = new static();
        $user->tin              = (string) $data['tin'];
        $user->type             = (string) $data['type'];
        $user->status           = "ACTIVE";
        $user->phone            = (string) $data['phone'];
        $user->email            = (string) $data['email'];
        $user->username         = (string) $data['username'];
        $user->parent_id        = (integer) $users_state_organisation->id;

        if (isset($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        foreach($data['name'] as $key => $value){
            $user->setTranslation('name', $key , $data['name'][$key]);
        }

        $user->save();

        return $user;
    }

    /**
     * Update a users profile.
     *
     * @param array $data
     * @return bool
     * @throws AppException
     */
    public function edit(array $data)
    {

        $this->getTranslation('name', "en");

        if (isset($data['name'])){
            $this->name = (string) $data['name'];
        }
        if (isset($data['new_password'])) {
            $this->changePassword($data);
        }
        if (isset($data['email'])) {
            $this->email = (string) $data['email'];
        }
        return $this->save();
    }

    /**
     * Determine whether the entered password is valid.
     *
     * @param string $password
     * @return  boolean
     */
    public function isCorrectPassword(string $password)
    {
        return (bool) password_verify($password, $this->password);
    }
    /**
     * Determine whether the entered password is valid.
     *
     * @param string $password
     * @return  boolean
     */
    public function isCorrectOldPassword(string $password)
    {
        return (bool) (md5($password) === $this->password);
    }

    /**
     * Returns a JWT token with access information.
     *
     * @return string
     */
    public function token()
    {
        return (string) JWTAuth::fromUser($this);
    }

    /**
     * Changes the password of the current user.
     *
     * @param array $data
     * @return  boolean
     * @throws AppException
     */
    public function changePassword(array $data)
    {
        if (!$this->isCorrectPassword($data['old_password'])) {
            throw new AppException(AppExceptionType::$INCORRECT_PASSWORD);
        }

        $this->password = password_hash($data['new_password'], PASSWORD_BCRYPT);

        return $this->save();
    }

    /**
     * Send the user an email to reset their password.
     *
     * @return boolean
     */
    public function passwordReset()
    {

        $this->forgot_token         = (string) str_random(60);
        $this->forgot_token_expires = Carbon::now()->addHours(24);
        $this->save();

        // mail to du

        return true;
    }

    /**
     * Generate Password Reset URL.
     *
     * @return boolean
     */

    public function generatePasswordResetUrl()
    {
        return config('api.url.reset') . $this->forgot_token;
    }

    /**
     * Get the total feedback count for the user.
     *
     * @return void
     */
    public function getFeedbackCount()
    {
        // $feedback_total = 0;

        // $spotlights = [];

        // foreach ($this->feedback as $feedback) {
        //     $feedback       = $feedback->indicator()->whereNotIn('spotlight_id', $spotlights)->pluck('spotlight_id')->toArray();
        //     $spotlights     = array_merge($spotlights, $feedback);
        //     $feedback_total += count($feedback);
        // }

        // foreach ($this->answers as $answer) {
        //     $answers            = $answer->question()->whereNotIn('spotlight_id', $spotlights)->pluck('spotlight_id')->toArray();
        //     $spotlights         = array_merge($spotlights, $answers);
        //     $feedback_total     += count($answers);
        // }

        // return $feedback_total;
    }


    public function contrat()
    {
        return $this->hasOne('App\Models\User\Contrat');
    }

    public function getCurrentPackageName()
    {
        $last_order = $this->orders
            ->where('type', 'ACTIVE')
            ->where('end_date','>',date("Y-m-d H:i:s"))
            ->first();
        if($last_order){
            return $last_order->package->name;
        } else {
            return 'Անվճար';
        }
    }

    public function getCurrentPackageStateName(){
        $user = $this->user();
        $organisation_id = $user->organisation->id;
        $order = OrderState::join("packages_state","packages_state.id","=","order_state.package_id")->where("order_state.organisation_id",$organisation_id)->where("order_state.type","ACTIVE")->where("order_state.deleted_at",null)->orderBy("order_state.id","DESC")->first();
        if(is_null($order)){
            return "Անվճար";
        }else{
            return $order->name;
        }
    }

    public function getCurrentPackageState(){
        $user = $this->user();
        $organisation_id = $user->organisation->id;
        $order = OrderState::join("packages_state","packages_state.id","=","order_state.package_id")->where("order_state.organisation_id",$organisation_id)->where("order_state.type","ACTIVE")->where("order_state.deleted_at",null)->orderBy("order_state.id","DESC")->first();
        if(is_null($order)){
            return "Անվճար";
        }else{
            return $order;
        }
    }

    public function getCurrentPackage()
    {
        $last_order = $this->orders
            ->where('type', 'ACTIVE')
            ->where('end_date','>',date("Y-m-d H:i:s"))
            ->first();
        if($last_order){
            return $last_order;
        } else {
            return null;
        }
    }


    
    public function selectedCpvs(){
        $user_cpvs_array = [];
        $user_cpvs = UserCpvs::where('user_id', $this->id)->with('cpv')->get();
        foreach ($user_cpvs as $cpv) {
            $user_cpvs_array[] = $cpv->cpv_id;
            $currCpv = Cpv::find($cpv->cpv_id);
            $currCpv->getParents($user_cpvs_array);
            $currCpv->getChildren($user_cpvs_array);
        }
        return array_values(array_unique($user_cpvs_array, SORT_REGULAR));
    }

    public function filters()
    {
        return $this->hasMany('App\Models\Settings\UserFilters', 'user_id', 'id')->with('package')->orderBy('package_id', 'DESC')->orderBy('end_date', 'DESC');
    }

    public function cpvs()
    {
        return $this->hasMany('App\Models\UserCategories\UserCpvs', 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order\Order', 'user_id', 'id')->with('package')->orderBy('package_id', 'DESC')->orderBy('end_date', 'DESC');
    }
    
    public function suggestions()
    {
        return $this->hasMany('App\Models\Suggestions\Suggestions', 'provider_id', 'id')
            ->where('responded', 0)
            ->where('is_signature', 0)
            ->where('seen', 0)
            ->with('organize')
            ->with('organizeItender');
    }

    public function packages()
    {
        return $this->hasManyThrough(
            'App\Models\Package\Package',
            'App\Models\Order\Order',
            'user_id',
            'id',
            'id',
            'package_id'
        );
    }

    public function organisation()
    {
        return $this->hasOne('App\Models\User\Organisation',"id","parent_id");
    }

    public function getDivisions()
    {
        return $this->hasMany('App\Models\User\User', 'parent_id', 'parent_id');
    }
    public function members()
    {
        return $this->hasMany('App\Models\User\Members', 'user_id', 'id');
    }
    public function wonLots()
    {
        return $this->hasMany('App\Models\Organize\OrganizeRow', 'winner_user_id', 'id')->with('procurementPlan');
    }
    public function lots()
    {
        return $this->hasMany('App\Models\Participant\ParticipantRow', 'participant_id', 'id');
    }
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

    public static function getGerupRootUserByGerupId($id)
    {
        return  User::where("parent_id",$id)->orderBy('divisions', 'DESC')->select("divisions","parent_id")->first();
    }

    public static function getGerupRootUser()
    {
        return  User::where("parent_id",auth('api')->user()->parent_id)->orderBy('divisions', 'DESC')->select("divisions","parent_id")->first();
    }

    public static function getGerupRootUserData(...$x)
    {
        return  User::where("parent_id",auth('api')->user()->parent_id)->orderBy('divisions', 'DESC')->select($x)->first();
    }
    public function childs()
    {
        $divisions = auth('api')->user()->divisions-1;
        return $this->hasMany('App\Models\User\User', 'parent_id', 'parent_id')->where("divisions",$divisions);
    }

    public function childsMembers()
    {
        return $this->hasMany('App\Models\User\User', 'parent_id', 'parent_id')->where("divisions",1)
            ->with("members");
    }

}
