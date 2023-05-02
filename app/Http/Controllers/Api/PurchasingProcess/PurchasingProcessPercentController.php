<?php


namespace App\Http\Controllers\Api\PurchasingProcess;


use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\PurchasingProcess\CreatePurchasingProcessPercentRequest;
use App\Http\Requests\PurchasingProcess\UpdatePurchasingProcessPercentRequest;
use App\Repositories\PurchasingProcess\PurchasingProcessPercentRepository;
use App\Services\PurchasingProcess\PurchasingProcessPercentService;
use App\Support\Transformers\PurchasingProcess\PurchasingProcessPercentTransformer;
use Illuminate\Http\JsonResponse;

class PurchasingProcessPercentController extends AbstractController
{

    /**
     * PurchasingProcessPercent.
     * @var     PurchasingProcessPercentRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $purchasingProcessPercent;

    /**
     * User controller constructor.
     *
     * @param PurchasingProcessPercentRepository $purchasingProcessPercent
     */
    public function __construct(PurchasingProcessPercentRepository $purchasingProcessPercent){
        parent::__construct();
        $this->purchasingProcessPercent = $purchasingProcessPercent;
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id){
        $paginator = $this->purchasingProcessPercent->retrieveById($id);
        return $this->respondWithItem($paginator, new PurchasingProcessPercentTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $purchasing_process_id
     * @return JsonResponse
     */
    public function getByPurchasingProcessId(int $purchasing_process_id){
        $paginator = $this->purchasingProcessPercent->getByPurchasingProcessId($purchasing_process_id);
        return $this->respondWithPagination($paginator, new PurchasingProcessPercentTransformer($this->shield->id()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePurchasingProcessPercentRequest $request
     * @return JsonResponse
     */
    public function store(CreatePurchasingProcessPercentRequest $request){
        $service = new PurchasingProcessPercentService($request);
        $service->createPurchasingProcessPercent();
        return $this->respondWithStatus(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CreatePurchasingProcessPercentRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdatePurchasingProcessPercentRequest $request, int $id){
        $service = new PurchasingProcessPercentService($request);
        $organize = $service->updatePurchasingProcessPercent($id);
        return $this->respondWithItem($organize, new PurchasingProcessPercentTransformer($this->shield->id()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id){
        $this->purchasingProcessPercent->retrieveById($id)->delete();
        return $this->respondWithStatus(true);
    }
}
