<?php


namespace App\Http\Controllers\Api\Settings;

use Auth;
use App\Models\Order\Order;
use App\Models\Package\PackageState;
use App\Http\Controllers\Api\AbstractController;
use App\Http\Controllers\Api\Mail\MailController;
use App\Repositories\Settings\PackageRepository;
use App\Support\Transformers\Settings\PackageTransformer;
use Illuminate\Http\JsonResponse;

class PackageController extends AbstractController
{

    /**
     * Package.
     * @var     PackageRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $package;

    /**
     * User controller constructor.
     *
     * @param PackageRepository $package
     */
    public function __construct(PackageRepository $package){
        parent::__construct();
        $this->package = $package;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $package = $this->package->get();
        return $this->respondWithItems($package, new PackageTransformer());
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id){
        $package = $this->package->retrieveById($id);
        return $this->respondWithItem($package, new PackageTransformer());
    }

    public function getPackageState(){
        $package = PackageState::get();
        return json_decode($package);
    }

}
