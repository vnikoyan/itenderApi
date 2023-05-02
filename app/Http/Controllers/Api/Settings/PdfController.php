<?php

namespace App\Http\Controllers\Api\Settings;

use App;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;

class PdfController extends Controller
{


    public function convertStringToHtml(Request $request)
    {
        error_reporting(0);
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'html' => ['required'],
            'name' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }
    	
	$name = explode("/",$data['name']);
	$name = implode($name);
    $name = $name."-".strtotime(date("Y-m-d H:i:s"));
        $fileName = public_path()."/files/html/".$name.".html";
        if (file_exists($fileName)) {
            @unlink($fileName);
        }
        file_put_contents($fileName, $data['html'], FILE_APPEND | LOCK_EX);
        chmod($fileName, 0777);
        return  response()->json(['error' => false, 'message' => "html file successfully created","name"=>$name]);
    }

}
