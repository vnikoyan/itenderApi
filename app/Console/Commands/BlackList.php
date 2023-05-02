<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use RtfHtmlPhp\Document;
use RtfHtmlPhp\Html\HtmlFormatter;

class BlackList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blacklist:up';

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
        $rtf = file_get_contents(public_path('uploads')."/1593003107.rtf"); 
		$document = new Document($rtf);
        echo $document;die;
		$formatter = new HtmlFormatter('UTF-8');
		echo( @ $formatter->Format($document) );die;
    }
}
