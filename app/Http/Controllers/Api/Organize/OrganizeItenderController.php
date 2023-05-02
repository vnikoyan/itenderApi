<?php
namespace App\Http\Controllers\Api\Organize;

use App\Repositories\Organize\OrganizeItenderRepository;
use App\Support\Transformers\Organize\OrganizeItenderTransformer;
use App\Http\Controllers\Api\AbstractController;
use App\Http\Requests\Organize\CreateOrganizeItenderRequest;
use App\Http\Requests\Organize\CreateOrganizePlanRequest;
use App\Http\Requests\Organize\UpdateOrganizeRequest;
use App\Http\Resources\Organize\OnePerson\OrganizeItenderResource;
use App\Http\Resources\Organize\OrganizeResource;
use App\Models\Organize\OrganizeItender;
use App\Services\Organize\OrganizeItenderService;
use App\Support\Transformers\Organize\OrganizeCardOnePersonTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class OrganizeItenderController  extends AbstractController
{
    /**
     * Organize.
     * @var     OrganizeItenderRepository
     * @access  protected
     * @since   1.0.0
    */
    protected $organize;
    /**
     * User controller constructor.
     *
     * @param OrganizeItenderRepository $organize
    */
    public function __construct(OrganizeItenderRepository $organize){
        parent::__construct();
        $this->organize = $organize;
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function show(int $id){
        $organize = $this->organize->retrieveById($id);
        // return new OrganizeItenderResource($organize);
        return $this->respondWithItem($organize, new OrganizeItenderTransformer($this->shield->id()));
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
    */
    public function getByUser(){
        $organize = $this->organize->getByProcurementByUser($this->shield->id());
        return $this->respondWithItems($organize, new OrganizeCardOnePersonTransformer($this->shield->id()));
    }

    public function getAll(Request $request){
        $objects = $this->organize->getAll($request);
        return OrganizeResource::collection($objects);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateOrganizeItenderRequest $request
     * @return JsonResponse
    */
    public function store(CreateOrganizeItenderRequest $request){
        $service = new OrganizeItenderService($request);
        $id = $service->createOrganize();
        return $this->respondWithStatus(true,["id" => $id]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param UpdateOrganizeRequest $request
     * @param int $id
     * @return JsonResponse
    */
    public function update(UpdateOrganizeRequest $request, int $id){
        $service = new OrganizeItenderService($request);
        $organize = $service->updateOrganize($id);
        return $this->respondWithItem($organize, new OrganizeItenderTransformer($this->shield->id()));
    }
    /**
     * Evolution of participants
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
    */
    public function evalution(Request $request, int $id){
        $service = new OrganizeItenderService($request);
        $organize = $service->evalutionOrganize($id);
        return $this->respondWithItem($organize, new OrganizeItenderTransformer($this->shield->id()));
    }
    /**
     * Set winner for organize
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
    */
    public function setWinner(Request $request, int $id){
        $service = new OrganizeItenderService($request);
        $organize = $service->setWinnerOrganize($id);
        return $this->respondWithItem($organize, new OrganizeItenderTransformer($this->shield->id()));
    }
    /**
     * Set confirmed report document.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
    */
    public function setReportDocument(Request $request, int $id){
        $service = new OrganizeItenderService($request);
        $organize = $service->setReportDocument($id);
        return $this->respondWithItem($organize, new OrganizeItenderTransformer($this->shield->id()));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function destroy(int $id){
        $this->organize->remove($id);
        return $this->respondWithStatus(true);
    }
    /**
     * Cancel the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function cancel(Request $request, int $id){
        $cancel_reason = $request->get('cancelReason');
        $this->organize->cancel($id, $cancel_reason);
        return $this->respondWithStatus(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return JsonResponse
    */
    public function getAllData(int $id){
        $organize = $this->organize->getAllData($id);
        return json_encode($organize->toArray());
    }

    public function contractFile(Request $request){
        return($request);
    }


    public function uploadAdditionalFile(int $id, Request $request):OrganizeItender{
        $organize = OrganizeItender::findOrFail($id);
        if(!empty($request->file('file'))){
            $value = $request->file('file');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $value->storeAs('/tender/organize_file',$fileNameToStore,"publicP");
            $fileURL = config('app.url')."/uploads/".$path;
            $organize->additional_file = $fileURL;
            $organize->save();
        }
        return $organize;
    }
}
