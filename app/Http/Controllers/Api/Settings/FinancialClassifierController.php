<?php


namespace App\Http\Controllers\Api\Settings;


use App\Repositories\Settings\FinancialClassifierRepository;
use App\Http\Controllers\Api\AbstractController;
use App\Support\Transformers\Settings\FinancialClassifierTransformer;

class FinancialClassifierController extends AbstractController
{
    /**
     * Users.
     *
     * @var     FinancialClassifierRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $financialClassifier;
    /**
     * User controller constructor.
     *
     * @param FinancialClassifierRepository $financialClassifier
    */
    public function __construct(FinancialClassifierRepository $financialClassifier){
        parent::__construct();
        $this->financialClassifier = $financialClassifier;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
    */
    public function index(){
        $financialClassifier = $this->financialClassifier->get();
        return $this->respondWithItems($financialClassifier, new FinancialClassifierTransformer($this->shield->id()));
    }

}