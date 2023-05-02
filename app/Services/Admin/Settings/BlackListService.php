<?php
namespace App\Services\Admin\Settings;

// Include any required classes, interfaces etc...
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Settings\BlackList;
use App\Imports\BlackList as BlackListImports;
use Smalot\PdfParser\Parser;
use DB;

class BlackListService
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

	public function createByFileUploade(){
		// $file = $this->request->file;
		// //    $inputFileType = 'Xlsx';
		// //    $inputFileType = 'Xml';
		// //    $inputFileType = 'Ods';
		// //    $inputFileType = 'Slk';
		// //    $inputFileType = 'Gnumeric';
		// //    $inputFileType = 'Csv';
		// // $array = \Excel::toArray(new BlackListImports, $this->request->file);
		// $rows = \Excel::toArray(new BlackListImports, $this->request->file);
		// $array_insert = [];

		// foreach($rows[0] as $key => $value){

		// 	try {
		// 		if( ( !empty($value[0]) && !empty($value[1]) && !empty($value[5]) ) ){
		// 		$end_date = str_replace("թ", "",$value[11]);
		// 		$start_date = str_replace("թ", "",$value[10]);
		// 			$array_insert[$key] = [
		// 				"name" => trim($value[1]),
		// 				"address" => trim($value[5]),
		// 				"for_what" => trim($value[7]),
		// 				"start_date" => date("Y-m-d", strtotime($start_date)),
		// 				"end_date" => date("Y-m-d", strtotime($end_date)),
		// 				"info"  => trim($value[13]),
		// 			];
					
		// 		}
		// 	} catch (Exception $e) {
		// 		echo $e->getMessage(), "\n";
		// 	} finally {
		
		// 	}
		// }	
		// DB::beginTransaction();
		
		// try{
		// 	DB::table('black_list')->truncate();
		// 	DB::table('black_list')->insert($array_insert);
		// 	DB::commit();
		// }catch(\Exception $e){
		// 	DB::rollback();
		// 	return false;		
		// }
		
		// return true;
		// $docFile = public_path('28.09.2021arm.docx');
	    // $filename = time().'_'.$file->getClientOriginalName();
	    // $location = public_path('uploads/fileConvert/');
     //    $scanFile = scandir($location);
	    // foreach($scanFile as $val){
	    //     if(is_file($location.$val)){
	    //     		unlink($location.$val);
	    //     	}
	    //     }

	    // $file->move($location,$filename);
	    // $filePath = public_path('uploads/fileConvert');
     //    if(count($scanFile)  == 3 ){
     //        $file = $scanFile[2];
     //        $file = $filePath.'/'.$file;
     //    }

		// $pdfFile = public_path('28.09.2021arm.pdf');
		$this->request->validate([
 					'file'   => 'required|file|mimes:pdf'
		]);

		$pdfFile = $this->request->file;
		$pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($pdfFile);
        $text = $pdf->getText();
        $regex = '/([0-9]{3,20}+\s+[0-9]{3,10}+\s+[0-9])|([0-9]{7,20}+\s+[0-9]{2})|([\d][0-9]\s?[0-9]{7,20})|([0-9]{7,20})/m';
        preg_match_all($regex, $text, $matches);
        BlackList::truncate();
        foreach ($matches[0] as $key => $val) {
       		$val = preg_replace('/\s+/', '', $val);
       		$blackList = new  BlackList;
       		$blackList->name = trim($val);
       		$blackList->save();
        }
    }
    
}

