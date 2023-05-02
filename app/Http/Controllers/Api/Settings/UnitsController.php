<?php
namespace App\Http\Controllers\Api\Settings;

use App\Repositories\Settings\UnitsRepository;
use App\Http\Controllers\Api\AbstractController;
use App\Support\Transformers\Settings\UnitsTransformer;
use Illuminate\Http\Request;
use App\Support\Exceptions\AppException;
use App\Support\Exceptions\AppExceptionType;
use Validator;

class UnitsController  extends AbstractController
{
    /**
     * Users.
     *
     * @var     UnitsRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $units;

    /**
     * User controller constructor.
     *
     * @param UnitsRepository $units
     */
    public function __construct(UnitsRepository $units){
        parent::__construct();
        $this->units = $units;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $units = $this->units->orderby('order')->get();
        return $this->respondWithItems($units, new UnitsTransformer($this->shield->id()));
    }

}
