<?php

namespace App\Http\Controllers;

use App;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spipu\Html2Pdf\Html2Pdf;
use App\Http\Controllers\Api\Settings\WordController;
use App\Models\Tender\TenderState;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use App\Models\Tender\TenderStateArchive;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Shared\Html;

class PdfController extends Controller
{

    public function __construct(){   
        $this->rotateFiles = array(
            "0" => "Կնքված պայմանագրի մասին հայտարարություն",
            "1" => "Գնահատման թերթիկ - դատարկ",
            "2" => "Գնահատման թերթիկ",
            "3" => "Կնքված պայմանագրի մասին հայտարարություն (ռուսերեն)",
            "4" => "Կնքված պայմանագրի հայտարարություն (ռուսերեն)",
        );
    }

    public function fixDb(){

        $tenders = TenderStateArchive::whereIn('id', function ( $query ) {
            $query->select('id')->groupBy('id')->havingRaw('count(*) > 1');
        })->get();

        $tenders = TenderStateArchive::select('id', 'invitation_link', 'password')
            ->whereIn('id', function ($q){
                        $q->select('id')
                        ->groupBy('invitation_link', 'password')
                        ->havingRaw('COUNT(*) > 1');
            })->get();


        $tenders = TenderStateArchive::select('id','invitation_link', DB::raw('COUNT(*) as `count`'))
            ->groupBy('invitation_link', 'password', 'type_name', 'tender_state_id', 'title')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        print($tenders->count());
        print('<br/>');

        foreach ($tenders as $tender) {
            print($tender);
            print('<br/>');
        }

        dd($tenders);
    }

    public function showDoccument(Request $request){
        error_reporting(0);
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'file' => ['required'],
            'is_with_footer' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }
        $name = trim($data['file']);
        $content = file_get_contents( public_path("files/html/").$name.".html");
        return view('documents.document',[ "html" => $content,
                                            "link" => $name,
                                            "is_with_footer" => $request->input('is_with_footer') === 'true' ? 1 : 0
                                        ]);
    }

    public function downloadDoccument(Request $request){
        error_reporting(0);
        $isValid = Validator::make($request->all()  , [
            'name' => ['required'],
            'file' => ['required'],
            'html' => ['required'],
            'is_with_footer' => ['required']
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }
        $name = $request->input('name');
        if($request->input('file') == "pdf"){
            ini_set("pcre.backtrack_limit", "5000000");
            $name = $request->input('name');
            $checkForRotateFile = false;


            $rotateFiles = array(
                "0" => "Կնքված պայմանագրի մասին հայտարարություն",
                "1" => "Գնահատման թերթիկ - դատարկ",
                "2" => "Գնահատման թերթիկ",
                "3" => "Կնքված պայմանագրի մասին հայտարարություն (ռուսերեն)",
                "4" => "Կնքված պայմանագրի հայտարարություն (ռուսերեն)",
                "5" => "Գնման հայտ",
            );


            foreach ($rotateFiles as $key => $value) {
                $val= trim(explode("-",$name)[0]);
                if($val === $value){
                    $checkForRotateFile = true; 
                }
            }

            $fileHtml = public_path("files/html/").$name.".html";
            $pdfFile =  public_path("files/pdf/").$name.".pdf";
            if (file_exists($pdfFile)) {
                @unlink($pdfFile);
            }
            $fileRead = $request->input("html");

            $before = array("․");
            $after   = array(".");
            $fileRead = str_replace($before, $after, $fileRead);
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            if($checkForRotateFile){
                $mpdf = new \Mpdf\Mpdf([
                 'mode' => 'utf-8',
                 'format' => 'A4-L',
                 'orientation' => 'L',
                 'fontDir' => array_merge($fontDirs, [
                    public_path() . '/css/fonts/',
                ]),
                'fontdata' => $fontData + [
                    'frutiger' => [
                        'R' => 'GHEAGrpalatReg.ttf',
                        'I' => 'GHEAGrpalatReg.ttf',
                    ]
                ],
                'default_font' => 'frutiger',
                ]);
            }else{
                $mpdf = new \Mpdf\Mpdf([
                    'fontDir' => array_merge($fontDirs, [
                        public_path() . '/css/fonts/',
                    ]),
                    'fontdata' => $fontData + [
                        'frutiger' => [
                            'R' => 'GHEAGrpalatReg.ttf',
                            'I' => 'GHEAGrpalatReg.ttf',
                        ]
                    ],
                    'default_font' => 'frutiger',
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 10,
                    'margin_bottom' => 20,
                    'margin_header' => 8,
                    'margin_footer' => 8
                ]);
            }
            // $mpdf->SetWatermarkText('DRAFT');
            // $mpdf->showWatermarkText = true;
            if($request->input('is_with_footer')){
                $footer = "
                <div style='color: #1E7BB7 !important; font-weight: bold !important;'>
                    iTender ©
                </div>|";
                $mpdf->defaultfooterline = 0;
                $mpdf->defaultfooterfontstyle='B';
                $mpdf->defaultfooterfontsize=11;
                $mpdf->SetFooter($footer, ['L']);
            }
            // $mpdf->autoPageBreak = false;
            $mpdf->WriteHTML($fileRead,\Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->Output($pdfFile);
            $pdfFile =  "/files/pdf/".$name.".pdf";
            return  response()->json(['error' => false, 'link' => $pdfFile]);
        }

        if($request->input('file') == "doc"){
            $checkForRotateFile = false;
            $name = $request->input('name');
            $docFile =  public_path("files/doc/").$name.".doc";
            $fileRead = $request->input("html");
            if (file_exists($docFile)) {
                @unlink($docFile);
            }


            $rotateFiles = array(
                "0" => "Կնքված պայմանագրի մասին հայտարարություն",
                "1" => "Գնահատման թերթիկ - դատարկ",
                "2" => "Գնահատման թերթիկ",
                "3" => "Կնքված պայմանագրի մասին հայտարարություն (ռուսերեն)",
                "4" => "Կնքված պայմանագրի հայտարարություն (ռուսերեն)",
                "5" => "Պայմանագրի նախագիծ",
            );

            foreach ($rotateFiles as $key => $value) {
                $val= trim(explode("-",$name)[0]);
                if($val === $value){

                    $checkForRotateFile = true; 
                }
            }


            // Word Controller 
            $word = new WordController;
	
            if($checkForRotateFile){
                $word->createDoc($fileRead, $docFile, 'landscape');
            }else{
                $word->createDoc($fileRead, $docFile, 'portrait');
            }

            $docFile = public_path()."/files/doc/".$name.".doc";
            $docFilePath =  "/files/doc/".$name.".doc";


            // // PHP Word  
            // $phpWord = new PhpWord();
            // $paper = new \PhpOffice\PhpWord\Style\Paper();
            // $paper->setSize('A4'); 
            // $section = $phpWord->addSection(array(
            //     'pageSizeW' => $paper->getWidth(), 
            //     'pageSizeH' => $paper->getHeight(), 
            //     'orientation' => 'landscape'
            // ));
            // Html::addHtml($section, 'htmlTemplate');
            // $docxFile = public_path()."/files/docx/".$name.".docx";
            // $docxFilePath = "/files/docx/".$name.".docx";
            // $phpWord->save($docxFile, 'Word2007');

            // // Google Doc 
            // $docContent = file_get_contents($docFile);

            // $content = file_get_contents($docxFile);
            // Log::info($content);
            // $files = Storage::disk('google')->files();
            // $firstFileName = $files[0];
            // $details = Storage::disk('google')->getMetadata($firstFileName);
            // $url = Storage::disk('google')->url($firstFileName);

            // Storage::disk("google")->put($name.".docx", $docContent);
            // $uploadedFileName = $name.".docx";
            // $details = Storage::disk('google')->getMetadata($uploadedFileName);
            // $url = Storage::disk('google')->url($uploadedFileName);

            // return  response()->json(['error' => false, 'link' => $url]);

            return  response()->json(['error' => false, 'link' => $docFilePath]);
        }
    }
}
