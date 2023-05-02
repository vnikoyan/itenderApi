<?php

namespace App\Http\Controllers\Api\Tender;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Models\Tender\UserTenders;
use App\Http\Controllers\Api\AbstractController;
class UserTendersController extends AbstractController
{

    public function __construct()
    {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'tender_state_id' => ['required', 'integer'],
            'applyed' => ['required'],
            'viewed' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $apply = ($request->input("apply") == "true") ? true : false ;
        $viewed = ($request->input("viewed") == "true") ? true : false ;
        $userTenders = new UserTenders;
        $userTenders->user_id = auth('api')->user()->id;
        $userTenders->tender_state_id = $request->input("tender_state_id");
        $userTenders->applyed = $apply;
        $userTenders->viewed = $viewed;
        $userTenders->save();
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

        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'tender_state_id' => ['required', 'integer'],
            'applyed' => ['required'],
            'viewed' => ['required'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $apply = ($request->input("apply") == "true") ? true : false ;
        $viewed = ($request->input("viewed") == "true") ? true : false ;
        UserTenders::where('id', $id)
                   ->update(['applyed' => $apply,"viewed" => $viewed]);
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
