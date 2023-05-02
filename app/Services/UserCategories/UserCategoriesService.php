<?php


namespace App\Services\UserCategories;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Models\UserCategories\UserCategories;

use Exception;

class UserCategoriesService
{
    use DispatchesJobs;

    /**
     * Incoming HTTP Request.
     *
     * @var Request;
     */
    protected $request;
    /** * User Service Class Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function storeUserCategories($user_id){
        UserCategories::where('user_id', $user_id)->delete();
        $categories = $this->request->all();
        return $this->builder($categories, $user_id);
    }

    private function builder($category, $user_id) {
        $insertArrayCategories = [];
        foreach ($category as $key => $category) {
            $insertArrayCategories[$key] = [
                "category_id" => $category['id'],
                "user_id" => $user_id,
            ];
        }
        UserCategories::insert($insertArrayCategories);
        return true;
    }

}
