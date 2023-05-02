<?php

namespace App\Http\Controllers\Api\Tender;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AbstractController;
use App\Models\Tender\Applications;

class ApplicationsController extends AbstractController
{
    public function get(Int $tender_id) {
        $user_id = auth('api')->user()->id;
        $application = Applications::where([['user_id', $user_id], ['tender_id', $tender_id]])->first();
        return $application;
    }

    public function set(Int $tender_id,  Request $request) {
        $user_id = auth('api')->user()->id;
        $application = Applications::where([['user_id', $user_id], ['tender_id', $tender_id]])->first();
        if(!$application){
            $application = new Applications();
        }
        $application->user_id = $user_id;
        $application->tender_id = $tender_id;
        $application->content = $request->input('content');
        $application->save();
        return $application;
    }
}
