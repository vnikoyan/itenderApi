<?php
namespace App\Services\Admin\Cpv;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\User\User;
use App\Jobs\ProcessNewCpvsUpload;
use App\Imports\CpvList;
use App\Models\Cpv\Cpv;
use App\Models\Cpv\Cpv2;
use App\Jobs\ProcessCpvTranslates;
use App\Models\Settings\Units;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


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

	public function uploadeTranslate(Request $request){
      if($request->file('fileTr')){
	        $file = $request->file('fileTr');
	        $filename = time().'_'.$file->getClientOriginalName();
	        $location = public_path('uploads/fileUpload/');
	        $files = scandir($location);
	        foreach($files as $val){
	        	if( file_exists($location.$val) &&is_file($location.$val) ){
	        		@unlink($location.$val);
	        	}
	        }
	        $file->move($location,$filename);
	        return redirect()->back()->with('message', 'Տվյալները շուտով կթարմացվեն');
     	}
	}
	
	public function uploade(){
		set_time_limit(1800);
		$file = $this->request->file;
		$rows = Excel::toArray(new CpvList, $this->request->file);
		ProcessNewCpvsUpload::dispatch($rows);
    }

}

