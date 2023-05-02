<?php

// Define the namespace
namespace App\Repositories\User;

// Include any required classes, interfaces etc...
use DB;
use App\Models\Settings\BlackList;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\Cerberus\Contracts\AuthProvider;
use App\Models\User\User;
use App\Models\Participant\Participant;
use App\Models\User\Organisation;
use Illuminate\Support\Facades\Log;

class UserRepository extends BaseRepository implements AuthProvider
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\User\User';
	}

    /**
     * Retrieves the user based on their id.
     *
     * @param integer $id
     * @return string
     */
	function retrieveById($id)
	{
		return $this->find($id);
	}

	/**
	 * Checks if username is available.
	 *
	 * @param $email
	 * @return bool
	 */
	public function isUsernameAvailable($email)
	{
		return (bool) $this->findByField('email', $email)->isEmpty();
	}

    /**
     * Search for top users based on the filter.
     *
     * @param string $q
     * @return User
     */
	public function searchBy($q)
	{
        
        $data = $this->with("organisation")->where('type', "USER")
            ->where(function ($query) use ($q) {
                $query->orWhere('name', 'like', '%' . $q . '%');
                $query->orWhere('email', 'like', '%' . $q . '%');
                $query->orWhere('tin', 'like', '%' . $q . '%');
            })->limit(10)->get();


		if(count($data)){
			foreach($data as $val){
				$checkUserOnBLackList = BlackList::where('name','like','%'.$val->tin.'%')->first();
				if(is_null($checkUserOnBLackList)){
					$val->blackList = false;
				}else{
					$val->blackList = true;
				}
			}
			return $data;
		} else {
			return ['status' => (boolean) BlackList::where('name','like','%'.$q.'%')->first()];
		}

	}

	public function searchByTin($q)
	{
        $data =[];

		$users = $this->with("organisation")->where('tin','LIKE',"%".$q."%")->with("organisation")->get();
        $participants = Participant::where('tin','LIKE',"%".$q."%")->get();

        foreach($users as $user){
			$organisation = Organisation::where('id', $user->organisation->id)->first();
			$user_details = json_encode($user->translations);
			$user_details_parsed = json_decode($user_details);
			$details = json_encode($organisation->translations);
			$details_parsed = json_decode($details);
            $item = [
				'is_user' => true,
                'id' => $user->id,
                'tin' => $user->tin,
                'phone' => $user->phone,
                'email' => $user->email,
				'name' => isset($details_parsed->name->hy) ? $details_parsed->name : $user_details_parsed->name,
                'address' => $details_parsed->address,
			];
            $data[] = $item;
        }
        foreach($participants as $participant){
			$details = json_encode($participant->translations);
			$details_parsed = json_decode($details);
			$item = [
				'is_user' => false,
                'id' => $participant->id,
                'tin' => $participant->tin,
                'phone' => $participant->phone,
                'email' => $participant->email,
                'name' => $details_parsed->name,
                'address' => $details_parsed->address,
			];
            $data[] = $item;
        }


		if(count($data)){
			foreach($data as &$val){
				$checkUserOnBLackList = BlackList::where('name','like','%'.$val['tin'].'%')->first();
				if(is_null($checkUserOnBLackList)){
					$val['blackList'] = false;
				}else{
					$val['blackList'] = true;
				}
			}
			$output = array_slice($data, 0, 10);
			return $data;

		} else {
			return ['status' => (boolean) BlackList::where('name','like','%'.$q.'%')->first()];
		}

	}


	public function getResponsibleUser($id)
	{
        return $this->with("members")->find($id);
	}



}
