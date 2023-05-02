<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class removePdfAndHtmlFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'removePdfAndHtml:files';

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
        $zipFIles = scandir(public_path('files/zip'));
        $docFiles  = scandir(public_path('files/doc'));
        $htmlFiles = scandir(public_path('files/html'));
        $pdfFiles  = scandir(public_path('files/pdf'));
        $zipPath = trim('/files/zip/ ');
        $docPath = trim('/files/doc/ ');
        $htmlPath = trim('/files/html/ ');
        $pdfPath = trim('/files/pdf/ ');
        foreach($zipFIles as $file){
            if(is_file(public_path().$zipPath.$file) && file_exists(public_path().$zipPath.$file) ){
                unlink(public_path().$zipPath.$file);
            }
        }
        foreach($docFiles as $file){
            if(is_file(public_path().$docPath.$file) && file_exists(public_path().$docPath.$file) ){
                unlink(public_path().$docPath.$file);
            }
        }
        foreach($htmlFiles as $file){
            if(is_file(public_path().$htmlPath.$file) && file_exists( public_path().$htmlPath.$file) ){
                unlink(public_path().$htmlPath.$file);
            }
        }
        foreach($pdfFiles as $file){
            if(is_file(public_path().$pdfPath.$file) && file_exists( public_path().$pdfPath.$file) ){
                unlink(public_path().$pdfPath.$file);
            }
        }

    }
}
