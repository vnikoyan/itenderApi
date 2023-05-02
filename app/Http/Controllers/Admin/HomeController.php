<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tender\Applications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends AbstractController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        $applications = Applications::with('tender')->get();
        $done_applications = [];
        $injury_sum = 0;
        foreach ($applications as $application) {
            $parsedContent = json_decode($application->content);
            $done = true;
            if(isset($parsedContent->submittedDocumentData)){
                $has_submittedDocumentData = count((array)$parsedContent->submittedDocumentData);
                if($has_submittedDocumentData){
                    if(isset($parsedContent->submittedDocumentData->sum)){
                        $injury_sum += (int)$parsedContent->submittedDocumentData->sum;
                    }
                }
            }
            if(count($parsedContent->selectedRows)){
                foreach ($parsedContent->selectedRows as $row) {
                    if(!isset($row->overall)){
                        $done = false;
                    }
                }
            } else {
                $done = false;
            }
            if($done){
                $done_applications[] = $application;
            }
        }
        $done_this_month_applications_count = 0;
        foreach ($done_applications as $application) {
            $created_date = $application->created_at;
            $is_this_month = date('m',strtotime($created_date)) == date('m');
            if($is_this_month){
                $done_this_month_applications_count++;
            }
        }
        $done_applications_count = count($done_applications);
        return view('admin.index.index',compact('done_applications_count', 'done_this_month_applications_count', 'injury_sum'));
    }



}
