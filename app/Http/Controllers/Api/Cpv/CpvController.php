<?php
namespace App\Http\Controllers\Api\Cpv;

use App\Repositories\Cpv\CpvRepository;
use App\Http\Controllers\Api\AbstractController;

use App\Support\Transformers\Cpv\GetCpvByIdsTransformer;
use App\Support\Transformers\Cpv\CpvRootTransformer;
use App\Support\Transformers\Cpv\CpvTransformer;
use App\Support\Transformers\Cpv\CpvSpecifications;
use App\Http\Requests\Cpv\GetCpvByIdsRequest;
use App\Http\Requests\Cpv\SetSpecificationsRequest;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;
use Illuminate\Http\Request;
use Validator;

class CpvController  extends AbstractController
{
    /**
     * Users.
     *
     * @var     CpvRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $cpv;
    /**
     * User controller constructor.
     *
     * @param CpvRepository $cpv
    */
    public function __construct(
        CpvRepository $cpv
    ){
        parent::__construct();

        $this->cpv = $cpv;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index($type)
    {
        $cpv = $this->cpv->getByTypeRoot($type);
        return $this->respondWithItems($cpv, new CpvRootTransformer($this->shield->id()));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $cpv = $this->cpv->getChlidrenById($id);
        if($cpv){
            return $this->respondWithItem($cpv, new CpvTransformer($this->shield->id()));
        }
       throw new AppException(AppExceptionType::$NOT_FOUND);

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function getSpecificationsByCpvId($id)
    {
        $cpv = $this->cpv->getSpecificationsByCpvId($id);
        $default = ["code","type", "name", "unit", "specifications"];
        $cpv->setDefault($default);
        $cpv->setAllowed($default);
        $cpv->setFillable($default);
        $cpv->relodeRequestParameterParser();
        if($cpv){
            return $this->respondWithItem($cpv, new CpvSpecifications($this->shield->id()));
        }
       throw new AppException(AppExceptionType::$NOT_FOUND);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function setSpecificationsByCpvId(SetSpecificationsRequest $request,$id)
    {
        $cpv = $this->cpv->setSpecificationsByCpvId($request,$id,$this->shield->id());
        if($cpv){
            return $this->respondWithStatus(true);
        }
        throw new AppException(AppExceptionType::$NOT_FOUND);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function updateSpecificationsByCpvId(SetSpecificationsRequest $request, $id)
    {
        $cpv = $this->cpv->updateSpecificationsByCpvId($request, $id);
        if($cpv){
            return $this->respondWithStatus(true);
        }
        throw new AppException(AppExceptionType::$NOT_FOUND);
    }
    
    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $cpv = $this->cpv->searchBy($request->q);
        return $this->respondWithPagination($cpv, new CpvTransformer($this->shield->id()));
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function searchArray(Request $request)
    {
        $cpv = $this->cpv->whereIn('code', $request)->select('id', 'code')->get();
        return $cpv;
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Support\Collection
    */
    public function getCpvByIds(GetCpvByIdsRequest $request)
    {
        $cpv = $this->cpv->getCpvByIds($request->ids);
        return $this->respondWithItems($cpv, new GetCpvByIdsTransformer($this->shield->id()));
    }

    /**
     * Display the specified resource.
     * @return \Illuminate\Support\Collection
    */
    public function getCpvChildIds(int $id)
    {
        $cpvs = $this->cpv->getCpvChildIds($id);
        return $this->respondWithItems($cpvs, new CpvRootTransformer($this->shield->id()));

    }

}
