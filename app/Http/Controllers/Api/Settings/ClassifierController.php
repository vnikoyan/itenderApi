<?php
namespace App\Http\Controllers\Api\Settings;

use App\Repositories\Settings\ClassifierRepository;
use App\Http\Controllers\Api\AbstractController;
use App\Support\Transformers\Settings\ClassifierTransformer;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;
use Illuminate\Http\Request;
use Validator;

class ClassifierController  extends AbstractController
{
    /**
     * Users.
     *
     * @var     ClassifierRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $classifier;

    /**
     * User controller constructor.
     *
     * @param UnitsRepository $units
    */
    public function __construct(ClassifierRepository $classifier){
        parent::__construct();
        $this->classifier = $classifier;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(){
        $classifier = $this->classifier->get();
        return $this->respondWithItems($classifier, new ClassifierTransformer($this->shield->id()));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function getByCpvId($cpv_id){
        $classifier = $this->classifier->getByCpvId($cpv_id);
        return $this->respondWithItems($classifier, new ClassifierTransformer($this->shield->id()));
    }

}
