<?php

namespace App\Http\Controllers\Api\UserCategories;

use App\Http\Controllers\Api\AbstractController;

use App\Services\UserCategories\UserCategoriesService;
use App\Repositories\UserCategories\UserCategoriesRepository;
use App\Http\Resources\UserCategories\UserCategoriesResource;

use Illuminate\Http\Request;

class UserCategoriesController extends AbstractController
{
    protected $suggestion;
    /**
     * Contract controller constructor.
     *
     * @param ParticipantRepository $participant
    */
    public function __construct(UserCategoriesRepository $suggestion){
        parent::__construct();
        $this->categories = $suggestion;
    }
    public function storeUserCategories(Request $request, int $id)
    {
        $service = new UserCategoriesService($request);
        $service->storeUserCategories($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserCategories()
    {
        $user_categories = $this->categories->getByUserId($this->shield->user()->id);
        return UserCategoriesResource::collection($user_categories);

    }

}
