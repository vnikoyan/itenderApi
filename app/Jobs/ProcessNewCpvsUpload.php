<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Cpv\Cpv;
use App\Models\Cpv\Cpv2;
use App\Http\Controllers\Api\Mail\MailController;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessNewCpvsUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$units_a = [];
		$parent_id1 = 0;
		$parent_id2 = 0;
		$parent_id3 = 0;
		$parent_id4 = 0;
		$parent_id5 = 0;
		$cat_type = 0;
		$array_insert_big = [];
		foreach($this->rows[1] as $key => $value){
				$cpv_code = (strlen(trim($value[0])) == 7) ? "0".$value[0] : $value[0];
				$cpv_pr_name = trim($value[1]);
				
				$cpv_unit = trim($value[2]);
				if(!empty($cpv_code)){
					$array_insert  = [
						"code" => $cpv_code,
						"name" => $cpv_pr_name,
						"unit" => $cpv_unit,
						"updated_at"=>date("Y-m-d H:i:s"),
					];
					$check = DB::table('cpv')->where('code',$cpv_code)->first();
					if(is_null($check)){
						if(substr($cpv_code, 0,2) < 45){
							$cat_type = 1;
						}elseif(substr($cpv_code, 0,2) > 45){
							$cat_type = 2;
						}else{
							$cat_type = 3;
						}
						$array_insert["type"] = $cat_type;
						$pos = strpos($cpv_code,'0',1);
						if($pos != 0){
							if($pos == 1){
								$cpvCode = substr($cpv_code, 0, $pos+1);
								$rest = substr($cpv_code, $pos+1 );
								$secondPos = strpos($rest,'0',1);
								$cpvCodeLength = strlen($cpv_code) - $pos-1 ;
								if($secondPos != false){
									$pos = $pos+$secondPos;
									$cpvCodeLength = strlen($cpv_code) - $pos-1 ;
								}
								$cpvCode = substr($cpv_code, 0, $pos); 
								
								for ($i=0; $i <= $cpvCodeLength; $i++) { 
									$cpvCode.="0";
								}
								$parentId = Cpv::where('code',$cpvCode)->first();
								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, $pos-1);
									for ($i=0; $i <= $cpvCodeLength+1; $i++) { 
										$cpvCode.="0";
									}
									$parentId = Cpv::where('code',$cpvCode)->first();
								}
								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, $pos-2);
									for ($i=0; $i <= $cpvCodeLength+2; $i++) { 
										$cpvCode.="0";
									}
									$parentId = Cpv::where('code',$cpvCode)->first();
								}
								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, $pos-3);
									for ($i=0; $i <= $cpvCodeLength+3; $i++) { 
										$cpvCode.="0";
									}
									$parentId = Cpv::where('code',$cpvCode)->first();
								}
								$parentId = (is_null($parentId)) ? 0 : $parentId->id;
								$array_insert["parent_id"] = $parentId;
								$array_insert_big[] = $array_insert;
								DB::table('cpv')->insert($array_insert);
								DB::table('cpv2')->insertOrIgnore($array_insert);
							}else{
								$cpvCode = substr($cpv_code, 0, $pos-1);
								$cpvCodeLength = strlen($cpv_code) - $pos ;
								for ($i=0; $i <= $cpvCodeLength; $i++) { 
									$cpvCode.="0";
								}
								$parentId = Cpv::where('code',$cpvCode)->first();
								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, $pos-2);
									for ($i=0; $i <= $cpvCodeLength+1; $i++) { 
										$cpvCode.="0";
									}
									$parentId = Cpv::where('code',$cpvCode)->first();
								}
								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, $pos-3);
									for ($i=0; $i <= $cpvCodeLength+2; $i++) { 
										$cpvCode.="0";
									}
									$parentId = Cpv::where('code',$cpvCode)->first();
								}
								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, $pos-4);
									for ($i=0; $i <= $cpvCodeLength+3; $i++) { 
										$cpvCode.="0";
									}
									$parentId = Cpv::where('code',$cpvCode)->first();
								}
								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, $pos-5);
									for ($i=0; $i <= $cpvCodeLength+4; $i++) { 
										$cpvCode.="0";
									}
									$parentId = Cpv::where('code',$cpvCode)->first();
								}
								$parentId = (is_null($parentId)) ? 0 : $parentId->id;
								$array_insert["parent_id"] = $parentId;
								$array_insert_big[] = $array_insert;
								DB::table('cpv')->insert($array_insert);
								DB::table('cpv2')->insertOrIgnore($array_insert);
							}
						}else{
							if($pos == false){
								$cpvCode = substr($cpv_code, 0, -1)."0";
								$parentId = Cpv::where('code',$cpvCode)->first();

								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, -2)."00";
									$parentId = Cpv::where('code',$cpvCode)->first();
								}

								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, -3)."000";
									$parentId = Cpv::where('code',$cpvCode)->first();
								}

								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, -4)."0000";
									$parentId = Cpv::where('code',$cpvCode)->first();
								}

								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, -5)."00000";
									$parentId = Cpv::where('code',$cpvCode)->first();
								}

								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, -6)."000000";
									$parentId = Cpv::where('code',$cpvCode)->first();
								}

								if(is_null($parentId)){
									$cpvCode = substr($cpv_code, 0, -7)."0000000";
									$parentId = Cpv::where('code',$cpvCode)->first();
								}

								$parentId = (is_null($parentId)) ? 0 : $parentId->id;
								$array_insert["parent_id"] = $parentId;
								$array_insert_big[] = $array_insert;
								DB::table('cpv')->insert($array_insert);
								DB::table('cpv2')->insertOrIgnore($array_insert);	
								}else{
									$rest = substr($cpv_code, 1 );
									$pos = strpos($rest,'0',1)+1;
									$cpvCode = substr($cpv_code, 0, $pos-1);
									$cpvCodeLength = strlen($cpv_code) - $pos ;
									for ($i=0; $i <= $cpvCodeLength; $i++) { 
										$cpvCode.="0";
									}
									$parentId = Cpv::where('code',$cpvCode)->first();
									if(is_null($parentId)){
										$cpvCode = substr($cpv_code, 0, $pos-1);
										for ($i=0; $i <= $cpvCodeLength; $i++) { 
											$cpvCode.="0";
										}
										$parentId = Cpv::where('code',$cpvCode)->first();
									}
									if(is_null($parentId)){
										$cpvCode = substr($cpv_code, 0, $pos-2);
										for ($i=0; $i <= $cpvCodeLength+1; $i++) { 
											$cpvCode.="0";
										}
										$parentId = Cpv::where('code',$cpvCode)->first();
									}
									$parentId = (is_null($parentId)) ? 0 : $parentId->id;
									$array_insert["parent_id"] = $parentId;
									$array_insert_big[] = $array_insert;
									DB::table('cpv')->insert($array_insert);
									DB::table('cpv2')->insertOrIgnore($array_insert);	
								}
							}
						}

					}
			}
		
		DB::table('units')->where("type",2)->delete();
		DB::table('units')->insert($units_a);
        if(count($array_insert_big)){
            $this->notifyAboutNewCpvs($array_insert_big);		
        }
		return true;	
    }

    public function notifyAboutNewCpvs($new_cpvs){

		$cpvs_string = '';

		foreach($new_cpvs as $cpv){
			$code = $cpv['code'];
			$name = $cpv['name'];
			$cpvs_string.= "<p>$code - $name</p></br>";
		}

		$cpvUsers = User::select("users.email","users.id","users.email_notifications")
			->join("order","order.user_id","=","users.id")
			->where("order.package_id","!=",1)
			->where("order.type","ACTIVE")
			->whereDate("order.end_date",">=",date("Y-m-d"))
			->groupBy("users.email")
			->get();
		$data = new \stdClass();
		$data->subject = "Ծանուցում";
		$data->text = "
			<div style='display: none; max-height: 0px; overflow: hidden;'>
				նոր կատեգորիաների մասին
			</div>
			<div style='display: none; max-height: 0px; overflow: hidden;'>
				&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
			</div>
			<p>Հարգելի գործընկեր</p><br>
			<p>Տեղեկացնում ենք, որ iTender համակարգում ավելացել են նոր կատեգորիաներ /CPV-կոդեր/։</p></br>
				$cpvs_string
			<p>Ավելացնելու համար առաջարկում ենք այցելել <a href='https://www.itender.am/settings'>Անձնական տվյալներ էջ</a></p></br></br></br>
			<p>Հարգանքով՝ iTender թիմ</p>";

		foreach($cpvUsers as $user){
			$data->email = trim($user['email']);
			ProcessNewTenderAdded::dispatch($data);
		}
	}
}
