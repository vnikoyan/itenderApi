<?php

namespace App\Http\Controllers\Api\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Settings\UserFiltersResource;
use App\Models\Settings\UserFilters;

class UserFiltersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth('api')->user()->id;
        $filters = UserFilters::where('user_id', $user_id)->first();
        if($filters){
            return new UserFiltersResource($filters);
        } else {
            return [];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = auth('api')->user()->id;
        $filters = UserFilters::where('user_id', $user_id)->first();
        if(!$filters){
            $filters = new UserFilters();
        }
        $filters->user_id = $user_id;
        $filters->status = json_encode($request->status);
        $filters->type = json_encode($request->type);
        $filters->organizator = json_encode($request->organizator);
        $filters->region = json_encode($request->region);
        $filters->procedure = json_encode($request->procedure);
        $filters->isElectronic = json_encode($request->isElectronic);
        $filters->guaranteed = json_encode($request->guaranteed);
        $filters->save();
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
