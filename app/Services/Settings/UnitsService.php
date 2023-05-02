<?php
namespace App\Services\Cpv;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\User\User;

class CpvService
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

	public function createUser(User $user){
        $user->name     = $this->request->name;
        $user->email    = $this->request->email;
        $user->phone    = $this->request->phone;
        $user->status   = $this->request->status;
        $user->tin      = $this->request->tin;
		$user->package_id  = $this->request->package_id;
        $user->expired  = $this->request->expired;
        $user->password = bcrypt($this->request->password);
		$user->save();
        return $user;
	}
	public function updateUser($id){
		$user = User::findOrFail($id);

		$data = $this->request->all();
		unset($data['password']);
		unset($data['password_confirmation']);

		foreach($data as $key => $request){
			if(is_array($request)){
				foreach($request as $ke => $value){
					$user->setTranslation($key , $ke , $value);
				}
			}else{
				$user->{$key}  = $request;
			}
		}
		if (isset($this->request->password)) {
            $user->password = password_hash($this->request->password, PASSWORD_BCRYPT);
        }
		$user->save();
		return $user;
	}

	public function createStateUser(User $user){
        $user->name     = $this->request->name;
        $user->email    = $this->request->email;
        $user->phone    = $this->request->phone;
        $user->status   = $this->request->status;
        $user->tin      = $this->request->tin;
        $user->address  = $this->request->address;
		$user->type     = "STATE";
        $user->password = bcrypt($this->request->password);
		$user->save();

		if($this->request->file('contract')){
			$this->contractUp($user);
		}
        return $user;
	}
	public function updateStateUser($id){
		$user = User::findOrFail($id);
		$user->name     = $this->request->name;
		$user->email    = $this->request->email;
		$user->phone    = $this->request->phone;
		$user->status   = $this->request->status;
        $user->address  = $this->request->address;
		$user->tin      = $this->request->tin;


		if($this->request->password){
			$user->password  = bcrypt($this->request->password);
		}
		$user->save();
		if($this->request->file('contract')){
			$this->contractUp($user);
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