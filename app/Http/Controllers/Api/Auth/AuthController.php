<?php

// Define the namespace
namespace App\Http\Controllers\Api\Auth;

// Include any required classes, interfaces etc...
use App\Models\User\User;
use App\Models\Order\Order;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\User\LoginRequest;
use App\Repositories\User\UserRepository;
use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\User\User\CreateUserRequest;
use App\Services\User\UserAuthenticationService;
use App\Http\Requests\User\CheckUsernameRequest;
use App\Http\Requests\User\FacebookConnectRequest;
use App\Http\Requests\Auth\ResetUserPasswordRequest;
use App\Http\Requests\User\ForgotUserPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Mail\MailController;
use App\Models\User\UserActivity;
use Illuminate\Support\Str;



class AuthController extends AbstractController
{



    protected $users;

    /**
     * Auth Constructor.
     *
     * @param UserRepository $users
     */
    public function __construct(
        UserRepository $users
    ){
        parent::__construct();
        
        $this->users = $users;
    }
    public function createUser(CreateUserRequest $request)
    {
        $service = new UserAuthenticationService($request);
        $token  = Str::random(32);
        $user = $service->createUser();
        if(empty($user->organisation->id_card_number)){
            $companyName = '«'.$user->organisation->name.'» '.$user->organisation->company_type.'-ի';
        } else {
            $companyName = $user->organisation->name.'-ի';
        }
        $url = \Config::get('values')['frontend_url']."/user/account/activate/".$token;
        $html = "<p>Բարի գալուստ iTender համակարգ</br><p>".$companyName." Գրանցումն ավարտելու և էլ․ հասցեն հաստատելու համար խնդրում ենք անցնել հետևյալ հղմամբ․</p></br><a href =".$url.">անցնել հղմամբ</a></br><p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
        $mailController = new MailController;
        $mailController->new_mail($user->email,'Գրանցում iTender համակարգում',$html );
        $user->verify_token = $token;
        $user->save();

        if($user->type == "user"){
            $order = new Order;
            $order->user_id = $user->id;
            $order->package_id = 1;
            $order->strat_date = date("Y-m-d H:i:s");
            $order->end_date = date('Y-m-d H:i:s', strtotime('+100 years'));
            $order->amount_paid = 0;
            $order->type = "ACTIVE";
            $order->save();
        }

        return $this->respondWithStatus(true, [
            'message' => 'Խնդրում ենք հաստատել էլ․ հասցեն մուտք գործելու համար',
        ], 201);
    }

    /**
     * Facebook Connect.
     *
     * The parameters:
     * - token (string) The facebook access token of the user
     *
     * @param   FacebookConnectRequest $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public function facebookConnect(FacebookConnectRequest $request)
    {
        $service = new UserAuthenticationService($request);
        $user = $service->facebookConnect();

        if($user->getAttribute('new_fb_user') === '1') {
            Log::info(__CLASS__ . '$$$$$$$$$$$$$$$$$$$$$$$$$$$$$ facebook sign in new user >> ' . $user->getAttribute('email'));
        }
        return $this->respondWithStatus(true, [
           'token' => $user->token()
        ]);
    }

    public function login(LoginRequest $request)
    {
        $service = new UserAuthenticationService($request);
        $user = $service->signInUser();

        if($user->type === "STATE"){
            $package = $user->getCurrentPackageStateName();
        } else {
            $package = $user->getCurrentPackageName();
        }

        $token = $user->token();

        $cookie = $this->getCookieDetails($token);

        $machine_id = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
        $user_id = $user->id;

        $user_activity = new UserActivity();
        $user_activity->user_id = $user_id;
        $user_activity->machine_id = $machine_id;
        $user_activity->login_time = date('Y-m-d H:i:s');
        $user_activity->save();

        return $this->respondWithStatus(true, [
            'token' => $token,
            'type' => $user->type,
            'first_login' => $user->first_login,
            'package' => $package,
        ])->withCookie($cookie['name'], $cookie['value'], $cookie['minutes'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly'], $cookie['samesite']);
    }

    public function logout()
    {
        $machine_id = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
        $user_activity = UserActivity::where([['machine_id', $machine_id], ['logout_time', null]])->first();
        $user_activity->logout_time = date('Y-m-d H:i:s');
        $user_activity->save();
    }

    public function isLoggedIn()
    {
        $machine_id = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
        $user_activity = UserActivity::where([['machine_id', $machine_id], ['logout_time', null]])->first();
        return (bool) $user_activity ? 'true' : 'false'; 
    }

    private function getCookieDetails($token)
    {
        return [
            'name' => '_token',
            'value' => $token,
            'minutes' => 1440,
            'path' => null,
            'domain' => null,
            // 'secure' => true, // for production
            'secure' => null, // for localhost
            'httponly' => true,
            'samesite' => true,
        ];
    }

    /**
     * Check username availability.
     *
     * The parameters:
     * - username (string) The username to search for
     *
     * @param CheckUsernameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function username(CheckUsernameRequest $request)
    {
        $status = $this->users->isUsernameAvailable($request->username);

        return $this->respondWithStatus($status);
    }

    /**
     * Send the user a password reset email.
     *
     * The parameters:
     * - email (string)     The email of the user
     *
     * @param   ForgotUserPasswordRequest $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public function forgot(ForgotUserPasswordRequest $request)
    {

        $service = new UserAuthenticationService($request);
        $status = $service->forgotPassword();
        return $this->respondWithStatus($status);
    }

    /**
     * Reset the users password.
     *
     * The parameters:
     * - token (string)                     The password reset token
     * - new_password (string)              The new password of the user
     * - new_password_confirmation (string) The new confirmed password of the new user
     *
     * @param   ResetUserPasswordRequest $request
     * @return  \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        $isValid = Validator::make($request->toArray(), [

            'token' => ['required'],
            'password' => ['required', 'min:6','max:255','confirmed'],
            'password_confirmation' => ['required', 'min:6'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $service = new UserAuthenticationService($request);
        $status = $service->resetPassword();

        return $this->respondWithStatus($status);
    }

}