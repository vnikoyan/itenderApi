<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Repositories\Categories\CategoriesRepository;
use App\Http\Controllers\Api\AbstractController;

use App\Support\Transformers\Category\CategoryRootTransformer;
use App\Support\Transformers\Category\CategoryTransformer;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;
use Illuminate\Http\Request;
use App\Models\Categories\Categories;
use Validator;

class CategoriesController extends AbstractController
{
    
    public function treeJson($type){
        $categories = Categories::where('parent',$type)->with('children')->get();  
        return  $categories->toJson();
    }
}
