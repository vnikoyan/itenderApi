<?php
namespace App\Http\Controllers\Api\User;

use App\Http\Requests\User\User\CreateUserRequest;
use App\Services\User\User\UserService;
use App\Repositories\User\UserRepository;
use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\User\User\UpdateUserRequest;
use App\Http\Requests\User\User\UpdateOrganisationRequest;
use App\Http\Requests\User\CreateUserResponsibleRequest;
use App\Http\Requests\User\CreateResponsibleMembersRequest;
use App\Http\Resources\User\UserPackageResource;
use App\Http\Resources\User\UserPackageStateResource;
use App\Support\Transformers\User\UserTransformer;
use App\Support\Transformers\User\UserResponsibleTransformer;
use App\Support\Transformers\User\UserNoOrganisationTransformer;
use App\Services\User\UserAuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Events\MyEvent;
use App\Events\NotificationEvent;
use App\Models\Contract\Contracts;
use Illuminate\Support\Facades\Log;
use Validator;

class UserController extends AbstractController
{
    /**
     * Users.
     *
     * @var     UserRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $users;

    /**
     * User controller constructor.
     *
     * @param UserRepository $users
     */
    public function __construct(
        UserRepository $users
    ){
        parent::__construct();

        $this->users = $users;
    }

    /**
     * Return the specified user.
     *
     * @param mixed $user_id
     * @return JsonResponse
     */
    public function show($user_id)
    {
        $user_id = $this->translateUserId($user_id);

        $user = $this->users->find($user_id);

        return $this->respondWithItem($user, new UserTransformer($this->shield->id()));
    }

    public function edit(UpdateUserRequest $request)
    {
        $service = new UserService($request);
        $user = $service->updateUser($this->shield->user()->id);
        if ( $user ) {
            return $this->respondWithItem($user, new UserTransformer($this->shield->id()),true);
        } else {
            return $this->respondWithStatus(false);
        }
    }

    public function editPrivateUser(UpdateUserRequest $request)
    {
        $service = new UserService($request);
        $user = $service->updatePrivateUser($this->shield->user()->id);
        if ( $user ) {
            return $this->respondWithItem($user, new UserTransformer($this->shield->id()),true);
        } else {
            return $this->respondWithStatus(false);
        }
    }

    public function connectTelegram(Request $request)
    {
        $service = new UserService($request);
        $user = $service->connectTelegram($this->shield->user()->id);
        if ( $user ) {
            return $this->respondWithItem($user, new UserTransformer($this->shield->id()),true);
        } else {
            return $this->respondWithStatus(false);
        }
    }

    public function checkAuth()
    {
        return auth('api')->user()->id;
    }

    public function testNotification()
    {
        // event(new NotificationEvent(292));
    }

    public function editPassword(Request $request)
    {
        $service = new UserService($request);
        return $service->updatePassword($this->shield->user()->id);
    }

    public function editOrganisation(UpdateOrganisationRequest $request)
    {
        $service = new UserService($request);
		$user = $service->updateOrganisation($this->shield->user()->parent_id);
        if ( $user ) {
            return $this->respondWithItem($user, new UserTransformer($this->shield->id()),true);
        } else {
            return $this->respondWithStatus(false);
        }
    }

    public function settings()
    {
        $user_id = $this->translateUserId(Auth()->user()->id);
        $this->users->find($user_id);
    }

    public function createUser(CreateUserRequest $request)
    {
        $service = new UserAuthenticationService($request);
        $user = $service->createUser();
        return $this->respondWithStatus(true, [
            'token' => $user->token()
        ], 201);
    }
    public function createUserByTin(CreateUserRequest $request)
    {
        // $service = new UserAuthenticationService($request);
        // $user    = $service->createUserByTin();

        // return $this->respondWithStatus(true,[
        //     'token' => $user->token()
        // ],201);

        // $user = User::firstOrNew(array('name' => Input::get('name')));
        // $user->foo = Input::get('foo');
        // $user->save();


    }
    public function logout()
    {
        Auth()->logout();
        return redirect('/');
    }

    public function getUserGrup()
    {
        $user =  $this->shield->getDivisions;
        return $this->respondWithItems($user, new UserNoOrganisationTransformer($this->shield->id()));
    }

    public function getUserChild()
    {
        $user = $this->shield->childs;
        return $this->respondWithItems($user, new UserNoOrganisationTransformer($this->shield->id()));
    }

    public function getRootUser()
    {
        $user = $this->users->find($this->shield->id());
        $user = $user->getGerupRootUserData("*");
        return $this->respondWithItem($user, new UserNoOrganisationTransformer($this->shield->id()));
    }
    public function me()
    {
        $user = $this->users->find($this->shield->id());
        // event(new NotificationEvent(292));
        return $this->respondWithItem($user, new UserTransformer($this->shield->id()));
    }

    public function getMenuNotifications()
    {
        $user = $this->users->find($this->shield->id());
        $unseen_suggestions = 0;
        $unseen_tenders = 0;
        $unseen_contract_requests = 0;
        // if($user->type == "USER"){
        //     foreach ($user->suggestions as $suggestion) {
        //         $organize = $suggestion->organize;
        //         if(!$organize){
        //             $organize = $suggestion->organizeItender;
        //         }
        //         if($organize){
        //             if($organize->is_canceled === 0 && $suggestion->seen === 0){
        //                 if(strtotime($organize->opening_date_time) >= strtotime(date('Y-m-d H:i:s'))){
        //                     $unseen_suggestions++;
        //                 }
        //             }
        //         }
        //     }
        //     $tenders = app('App\Http\Controllers\Api\Tender\TenderController')->getUserTendersById($user->id);
        //     foreach ($tenders as $tender) {
        //         if(!$tender->isViewed()){
        //             $unseen_tenders++;
        //         }
        //     }
        // }
        $unseen_contract_requests = Contracts::where("is_sign", 0)->where("provider_user_id", $user->id)->count();
        // $orders = app('App\Repositories\Contract\ContractOrdersRepository')->getByProvider($user->id, 'active');
        // $active_orders = $orders->get();
        $result = [
            'unseen_notifications' => 0,
            'unseen_tenders' => $unseen_tenders,
            'active_orders' => 0,
            'unseen_suggestions' => $unseen_suggestions,
            'unseen_contract_requests' => $unseen_contract_requests
        ];
        return $result;
    }


    public function getPackage()
    {
        $user = $this->users->find($this->shield->id());
        if($user->type == "STATE"){
            return new UserPackageStateResource($user->getCurrentPackageState());
        }else{
            $package = $user->getCurrentPackage();
            if($package){
                return new UserPackageResource($package);
            } else {
                return $package;
            }
        }
    }
    public function search(Request $request)
    {
        $user = $this->users->searchBy($request->q);
        return json_encode($user);
    }
    public function getRealBeneficiariesDeclaration()
    {
        $user = $this->users->find($this->shield->id());
        return $user->real_beneficiaries_declaration_html;
    }
    

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
      */
    public function createStateResponsibleUser(CreateUserResponsibleRequest $request){
        $service = new UserService($request);
        $service->createStateResponsibleUser();
        return $this->respondWithStatus(true);
    }

    public function updateResponsibleUser(Request $request, int $id){
        $service = new UserService($request);
        $service->updateResponsibleUser($id);
        return $this->respondWithStatus(true);
    }

    public function getResponsibleUser(){
        $user = $this->users->getResponsibleUser($this->shield->id());
        $default = ["id","members"];
        $user->setDefault($default);
        $user->setAllowed($default);
        $user->setFillable($default);
        $user->relodeRequestParameterParser();

        return $this->respondWithItem($user, new UserResponsibleTransformer($this->shield->id()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function getUserMembers(int $id){
        $user = $this->users->getResponsibleUser($id);
        $default = ["id","members"];
        $user->setDefault($default);
        $user->setAllowed($default);
        $user->setFillable($default);
        $user->relodeRequestParameterParser();
        return $this->respondWithItem($user, new UserResponsibleTransformer($this->shield->id()));
    }

    public function getUserChildMembers()
    {
        $user =  $this->shield->childsMembers;
        return $this->respondWithItems($user, new UserResponsibleTransformer($this->shield->id()));
    }

    public function postUserChildMembers(CreateResponsibleMembersRequest $request,int $user_id)
    {
        $service = new UserService($request);
        $service->postUserChildMembers($user_id);
        return $this->respondWithStatus(true);
    }

    public function putUserChildMembers(CreateResponsibleMembersRequest $request,int $member_id)
    {
        $service = new UserService($request);
        $service->putUserChildMembers($member_id);
        return $this->respondWithStatus(true);
    }

    public function deleteUserChildMembers(Request $request,int $member_id)
    {
        $service = new UserService($request);
        $service->deleteUserChildMembers($member_id);
        return $this->respondWithStatus(true);
    }

    public function searchByTin(Request $request){
        $result = $this->users->searchByTin($request->tin);
        return json_encode($result);
    }


}
