<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cpv\Cpv;
use App\Imports\CpvList;

class CpvTranslateFileCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CpvTranslat:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filePath = public_path('uploads/fileUpload');
        $scanFile = scandir($filePath);
        if(count($scanFile)  == 3 ){
            $file = $scanFile[2];
            $file = $filePath.'/'.$file;
            $rows = \Excel::toArray(new CpvList, $file);
            $row = $rows[1];
            foreach($row as $key => $value){
                $cpv_code = (strlen(trim($value[3])) == 7) ? "0".$value[3] : $value[3];
                $cpv = Cpv::where('code',$cpv_code)->first();
                if( !is_null($cpv) ){
                    $cpv_name = trim($value[4]);
                    $cpv_unit = trim($value[5]);
                    $cpvId = $cpv->id; 
                    Cpv::where('id',$cpvId)->update(['name_ru'=> $cpv_name ,'unit_ru'=> $cpv_unit]);            
                }
            }
            @unlink($file);
        }
    }
}
