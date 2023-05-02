<?php
namespace App\Services\User\User;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use App\Models\User\User;
use App\Models\Order\OrderState;
use App\Models\Package\PackageState;
use App\Models\User\Organisation;
use App\Models\User\Members;
use App\Models\User\Contrat;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Controllers\Api\Mail\MailController;
use Illuminate\Support\Facades\Log;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;

class UserService
{
	use DispatchesJobs;

	/**
	 * Incoming HTTP Request.
	 *
	 * @var \Illuminate\Http\Request;
	 */
	protected $request;

	/**
	 * User Service Class Constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request){
		$this->request = $request;

	}

	//TODO CHANGE
	public function createUser(User $user){
        $user->name     = $this->request->name;
        $user->email    = $this->request->email;
        $user->phone    = $this->request->phone;
        $user->status   = $this->request->status;
        $user->tin      = $this->request->tin;
		// $user->package_id  = $this->request->package_id;
        // $user->expired  = $this->request->expired;
		$user->save();
        return $user;
	}

	public function updateUser($id){
		$user = User::findOrFail($id);

		$data = $this->request->all();
		unset($data['_method']);
		unset($data['_token']);
		unset($data['password']);
		unset($data['password_confirmation']);
		if(isset($data['company_name'])){
			$company_name  = $data['company_name'];
			$users_state_organisation = Organisation::select("users_state_organisation.name as ogName")
													->where("users_state_organisation.id",$user->organisation['id'])
													->first();
			$ogName = json_decode($users_state_organisation->ogName);
			$ogName = ['hy' => $company_name];
			Organisation::where('id',$user->organisation['id'])->update(['name'=> json_encode($ogName)]);
			unset($data['company_name']);
		}
		foreach($data as $key => $request){
			if(is_array($request)){
				foreach($request as $ke => $value){
					$user->setTranslation($key , $ke , $value);
				}
			}else{
				$user->{$key}  = $request;
			}
		}
		
		$user->save();
		return $user;
	}

	public function connectTelegram($id)
	{
		$user = User::findOrFail($id);
		$user->telegram_id            = (string) $this->request->id;
        $user->telegram_username      = (string) $this->request->username;
        $user->email_notifications    = true;
        $user->save();
		// $content = 'Awesome *bold* text and [inline URL](http://www.example.com/)'
		$content = '
		Բարի գալուստ [iTender](https://www.itender.am/)
Այսուհետ դուք կարող եք ստանալ ծանուցումները նաև այստեղ';
        Notification::send($user, new SendNotification($user->id, $content, false));
        return $user;
	}

	public function updatePrivateUser($id){
		$user = User::findOrFail($id);

        $users_state_organisation = Organisation::findOrFail($user->organisation['id']);
        $users_state_organisation->tin = (string) $this->request->tin;
        $users_state_organisation->phone = (string) $this->request->phone;
        $users_state_organisation->bank_account = (string) $this->request->bank_account;

        $users_state_organisation->id_card_number = (string) $this->request->id_card_number;
        $users_state_organisation->passport_serial_number = (string) $this->request->passport['serial_number'];
        $users_state_organisation->passport_given_at = (string) $this->request->passport['given_at'];
        $users_state_organisation->passport_from = (string) $this->request->passport['from'];
        $users_state_organisation->passport_valid_until = (string) $this->request->passport['valid_until'];

		foreach($this->request->name as $key => $value){
			isset($this->request->company_type['hy']) && isset($this->request->company_type['ru']) && $users_state_organisation->setTranslation('company_type', $key , $this->request->company_type[$key]);
			isset($this->request->director_name['hy']) && isset($this->request->director_name['ru']) && $users_state_organisation->setTranslation('director_name', $key , $this->request->director_name[$key]);
			$users_state_organisation->setTranslation('name', $key , $this->request->name[$key]);
			$users_state_organisation->setTranslation('region', $key , $this->request->region[$key]);
			$users_state_organisation->setTranslation('city', $key , $this->request->city[$key]);
			$users_state_organisation->setTranslation('address', $key , $this->request->address[$key]);
			$users_state_organisation->setTranslation('bank_name', $key , $this->request->bank_name[$key]);
		}

        $users_state_organisation->save();

        $user->tin              = (string) $this->request->tin;
        $user->status           = "ACTIVE";
        $user->phone            = (string) $this->request->phone;
        $user->email            = (string) $this->request->email;
        $user->username         = (string) $this->request->username;
        $user->parent_id        = (integer) $users_state_organisation->id;

        foreach($this->request->name as $key => $value){
            $user->setTranslation('name', $key , $this->request->name[$key]);
        }

        $user->save();

        return $user;
	}

	

	public function updatePassword($id)
	{
		$user = User::findOrFail($id);
		if ( ($user && $user->isCorrectPassword($this->request->input('old_password'))) || ($user && $user->isCorrectOldPassword($this->request->input('old_password'))) ) {
			if (isset($this->request->password)) {
				Log::info('here');
				$user->password = password_hash($this->request->password, PASSWORD_BCRYPT);
				Log::info($user->password);
				$user->save();
				return 'success';
			}
		} else {
			return 'wrong old password';
		}

		return $user;
	}

	public function updateOrganisation($id){
		$user = Organisation::findOrFail($id);

		$data = $this->request->all();
		foreach($data as $key => $request){
			if(is_array($request) && isset($request['hy'])){
				foreach($request as $ke => $value){
					$user->setTranslation($key , $ke , $value);
				}
			}else{
				$user->{$key}  = $request;
			}
		}
		$user->save();
		return $user;
	}

    public function createStateResponsibleUser(){
	    $pass = \Str::random(8);
//		//TODO chnege test
		$newDivisions = new User();
        $newDivisions->name       = $this->request->name;
        $newDivisions->position   = json_encode($this->request->position);
		$newDivisions->email      = $this->request->email;
        $newDivisions->divisions  = 1;
        $newDivisions->type       = "STATE";
        $newDivisions->username   = $this->request->username;
		$newDivisions->parent_id  = auth('api')->user()->parent_id;
        $newDivisions->status     = "ACTIVE";

        //TODO chnege line 82
        //     $user->password = bcrypt($pass);
        //TODO send maile

        $newDivisions->password = bcrypt($this->request->password);
		$newDivisions->save();

		foreach ($this->request->members as $key => $value){
		    $members = new Members();
		    $members->name = $value['name'];
		    $members->position = $value['position'];
		    $members->user_id = $newDivisions->id;
		    $members->save();
        }

    }

	public function updateResponsibleUser($id){
		$division = User::findOrFail($id);
        $division->name       = $this->request->name;
		$division->email      = $this->request->email;
		if($this->request->password !== '******'){
			$division->password	  = bcrypt($this->request->password);
		}
		$division->save();
		return $division;
    }

    public function postUserChildMembers(int $id){
	    $members = new Members();
	    $members->name = $this->request->name;
	    $members->position = $this->request->position;
	    $members->user_id = $id;
	    $members->save();
    }
    public function deleteUserChildMembers(int $member_id){
       Members::where("id",$member_id)->delete();
    }
    public function putUserChildMembers(int $member_id){
        $members = Members::where("id",$member_id)->firstOrFail();

        if(!empty($this->request->name)) {
            $members->name = $this->request->name;
        }
		if(!empty($this->request->position)) {
            $members->position = $this->request->position;
        }
        $members->save();
    }
	public function createStateDivisionsUser($user,$organisation_id){
		$pass = \Str::random(8);
		//TODO chnege test
		$newDivisions = new User();
        $newDivisions->name       = $this->request->c_name;
		$newDivisions->email      = $this->request->c_email;
        $newDivisions->divisions  = $this->request->c_type;
        $newDivisions->username   = $this->request->c_username;
        $newDivisions->type       = "STATE";
		$newDivisions->parent_id  = $organisation_id;
        $newDivisions->status     = "ACTIVE";
        $newDivisions->is_confirmed = true;

        //TODO chnege line 77
        //     $user->password = bcrypt($pass);
        //TODO send maile
        $newDivisions->password = bcrypt(123456);
		$newDivisions->save();
        return $user;
	}
	public function updateStateUser($id){

		$user = Organisation::findOrFail($id);

		if(!empty($this->request->name)){
			$user->name     = ['hy' => $this->request->name, 'ru' => $this->request->name_ru];
		}
		if(!empty($this->request->email)){
			$user->email    = $this->request->email;
		}
		if(!empty($this->request->phone)){
			$user->phone    = $this->request->phone;
		}
		if(!empty($this->request->status)){
			$user->status   = $this->request->status;
		}
		if(!empty($this->request->address)){
			$user->address  = $this->request->address;
		}
		if(!empty($this->request->tin)){
			$user->tin      = $this->request->tin;
		}
		if(!empty($this->request->divisions)){
			$user->divisions  = $this->request->divisions;
		}
		if($this->request->password){
			$user->password  = bcrypt($this->request->password);
		}
		$user->save();

		if($this->request->file('contract')){
			$this->contractUp($user);
		}

		$package_id = $this->request->package_id;
		$startDate  = $this->request->startDate;
		$endDate    = $this->request->endDate;
		$orderId    = $this->request->orderId;
		$mailController = new MailController;
		$subject = "Փաթեթի ակտիվացում";
		$userEmail = Organisation::where("id",$id)->with('user')->first();
        if($userEmail->id_card_number){
            $companyName = $userEmail->name;
        } else {
            $companyName = '«'.$userEmail->name.'» '.$userEmail->company_type;
        }  
		$userEmail->email =  $userEmail->user[0]->email;
		$order      = OrderState::where("id",$orderId)->first();
		if(!is_null($package_id) && !is_null($startDate) && !is_null($endDate)){
			$package    = PackageState::where("id",$package_id)->select("price","name")->first();  
			$pName = $package->name; 
			if( is_null($orderId) && !is_null($package_id) ){
				OrderState::where("organisation_id", $id)->update(["type" => "PASSIVE"]);
				$orderState = new OrderState();
				$orderState->strat_date = $startDate;
				$orderState->end_date = $endDate;
				$orderState->payment_method = "added by admin";
				$orderState->amount_paid = $package->price;
				$orderState->type = "ACTIVE";
				$orderState->package_id = $package_id;
				$orderState->organisation_id = $id;
				$orderState->payment_aproved = 1;
				$orderState->save();
				$html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, iTender համակարգի ծառայությունների փաթեթը ՝ ".$pName.", ակտիվացված է, որն ակտիվ է ".$startDate."-ից ".date("Y-m-d", strtotime($endDate))."-ը ներառյալ։</p></br>
                        <p>iTender համակարգից օգտվելու <a href = 'https://www.youtube.com/channel/UCD3gwb1H1NE9qujwPzCd3pg/videos'> ուղեցույց </a></p></br>
                        <p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
                $mailController->new_mail(trim($userEmail->email),$subject,$html);
			}else{

				if( !empty($order) && $order->package_id == $package_id){
					OrderState::where("id",$orderId)->update(["strat_date" => $startDate, "end_date" => $endDate]);

				}else{

					OrderState::where("organisation_id", $id)->update(["type" => "PASSIVE"]);
					$orderState = new OrderState();
					$orderState->strat_date = $startDate;
					$orderState->end_date = $endDate;
					$orderState->payment_method = "added by admin";
					$orderState->amount_paid = $package->price;
					$orderState->type = "ACTIVE";
					$orderState->package_id = $package_id;
					$orderState->organisation_id = $id;
					$orderState->payment_aproved = 1;
					$orderState->save();
					$html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, iTender համակարգի ծառայությունների փաթեթը ՝ ".$pName.", ակտիվացված է, որն ակտիվ է ".$startDate."-ից ".date("Y-m-d", strtotime($endDate))."-ը ներառյալ։</p></br>
                        <p>iTender համակարգից օգտվելու <a href = 'https://www.youtube.com/channel/UCD3gwb1H1NE9qujwPzCd3pg/videos'> ուղեցույց </a></p></br>
                        <p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
                $mailController->new_mail(trim($userEmail->email),$subject,$html);
				}
			}
		}
		if( !empty($this->request->package_id_trial) && !empty($this->request->startDate_trial) && !empty($this->request->endDate_trial) ){
			$orderTrial = OrderState::where("id",$this->request->orderIdTrial)->first();
				$package    = PackageState::where("id",$this->request->package_id_trial)->select("price","name")->first();  
				$pName = $package->name; 
			if(is_null($orderTrial)){
				OrderState::where("organisation_id", $id)->update(["type" => "SUSPENDED"]);
				$package = PackageState::where("id",$this->request->package_id_trial)->first();
				$orderState = new OrderState();
				$orderState->strat_date = $this->request->startDate_trial;
				$orderState->end_date = $this->request->endDate_trial;
				$orderState->payment_method = "trial period";
				$orderState->amount_paid = $package->price;
				$orderState->type = "ACTIVE";
				$orderState->package_id = $this->request->package_id_trial;
				$orderState->organisation_id = $id;
				$orderState->payment_aproved = 1;
				$orderState->save();
				$subject = "ԱՆՎՃԱՐ փորձաշրջանի ակտիվացում";
				$html = "<p>".$companyName."</p></br><p>Հարգելի գործընկեր, Դուք ակտիվացրել եք iTender համակարգից անվճար օգտվելու փորձաշրջան, որի ընթացքում կարող եք օգտվել ".$pName." փաթեթի բոլոր հնարավորություններից։ Փորձաշրջանի ժամկետն ավարտվում է ".date('Y-m-d', strtotime($this->request->endDate_trial))."-ին:</p></br>
                            <p>Շնորհակալություն</p></br><p>Հարգանքով՝ iTender թիմ</p>";
                    $mailController->new_mail(trim($userEmail->email),$subject,$html);
			}else{
				OrderState::where("organisation_id", $id)->where("payment_method","trial period")->update(["type" => "SUSPENDED"]);
				OrderState::where("id",$this->request->orderIdTrial)->update(["strat_date" => $this->request->startDate_trial, "end_date" => $this->request->endDate_trial,"type" => "ACTIVE"]);
			}
		}

		return $user;
	}

	public function contractUp($user)
	{
		$filenameWithExt = $this->request->file('contract')->getClientOriginalName();
		$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
		$extension = $this->request->file('contract')->getClientOriginalExtension();
		$fileNameToStore = $filename.'_'.time().'.'.$extension;
		$path = $this->request->file('contract')->storeAs('/public/contract',$fileNameToStore);
    //

		if(empty($user->contrat)){
			$contrat = new Contrat();
			$contrat->user_id = $user->id;
			$contrat->file = $fileNameToStore;

			$contrat->save();
		}else{
			$user->contrat->file = $fileNameToStore;
			$user->contrat->save();
		}

		return $user;
	}
}
