<?php

namespace App\Http\Controllers\Api\UserCategories;

use App\Http\Controllers\Api\AbstractController;

use App\Services\UserCategories\UserCpvsService;
use App\Repositories\UserCategories\UserCpvsRepository;
use App\Http\Resources\UserCategories\UserCpvsResource;
use App\Models\Cpv\Cpv;
use App\Models\UserCategories\UserCpvs;
use Illuminate\Http\Request;

class UserCpvsController extends AbstractController
{
    protected $suggestion;
    /**
     * Contract controller constructor.
     *
     * @param ParticipantRepository $participant
    */
    public function __construct(UserCpvsRepository $suggestion){
        parent::__construct();
        $this->cpvs = $suggestion;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserCpvs()
    {
        $user_id = $this->shield->user()->id;
        $user_cpvs = array_keys(UserCpvs::where('user_id', $user_id)->get()->keyBy('cpv_id')->toArray());
        $cpvs = Cpv::whereIn('id', $user_cpvs)->withCount('tenderStateRow')->get();
        return UserCpvsResource::collection($cpvs);

    }

    public function storeUserCpvs(Request $request, int $id)
    {
        $service = new UserCpvsService($request);
        $service->storeUserCpvs($id);
        return $this->respondWithStatus(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $suggestion = $this->suggestion->getByProviderId($id);
        return $suggestion;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contracts  $contracts
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->contract->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }
}
