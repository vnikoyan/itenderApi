<?php
namespace App\Services\Admin\Admin;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Spatie\Permission\Models\Role;
use App\Models\Admin;
use DB;

class AdminService
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

	public function createAdmin(Admin $admin){
        $admin->name     = $this->request->name;
        $admin->email    = $this->request->email;
        $admin->user_name    = $this->request->user_name;
        $admin->password = bcrypt($this->request->password);
		$admin->save();
		$role = Role::create(['name' => "role_".$admin->id]);

		if(!empty($this->request->permission)){
			$role->syncPermissions(array_keys($this->request->permission));
			$admin->assignRole($role->name);
		}
        return true;
	}
	public function updateAdmin($id){
		$admin = Admin::findOrFail($id);
		$admin->name     = $this->request->name;
        $admin->user_name    = $this->request->user_name;
		$admin->email    = $this->request->email;
		if($this->request->password){
			$admin->password  = bcrypt($this->request->password);
		}
		$admin->save();
	}
	public function updatePermission($id){
		$admin = Admin::findOrFail($id);

		DB::table('model_has_roles')->where('model_id',$id)->delete();
		if(!empty($this->request->permission)){
			$role = Role::findByName("role_".$admin->id);
			$role->syncPermissions(array_keys($this->request->permission));
			$admin->assignRole($role->name);
		}

	}
}
