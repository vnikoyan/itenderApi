<?php

namespace App\Http\Controllers\Api\Categories;

use App\Repositories\Categories\CategoriesRepository;
use App\Http\Controllers\Api\AbstractController;

use App\Support\Transformers\Category\CategoryRootTransformer;
use App\Support\Transformers\Category\CategoryTransformer;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;
use Illuminate\Http\Request;
use Validator;

class CategoriesController extends AbstractController
{
    /**
     * Users.
     *
     * @var     CategoriesRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $category;
    /**
     * User controller constructor.
     *
     * @param CategoriesRepository $category
    */
    public function __construct(
        CategoriesRepository $category
    ){
        parent::__construct();

        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index($type)
    {
        $category = $this->category->getByTypeRoot($type);
        return $this->respondWithItems($category, new CategoryRootTransformer($this->shield->id()));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $category = $this->category->getChlidrenById($id);
        if($category){
            return $this->respondWithItem($category, new CategoryTransformer($this->shield->id()));
        }
        throw new AppException(AppExceptionType::$NOT_FOUND);
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $category = $this->category->searchBy($request->q);
        return $this->respondWithPagination($category, new CategoryTransformer($this->shield->id()));
    }
    
}
