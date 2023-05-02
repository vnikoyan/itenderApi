<?php


namespace App\Http\Controllers\Api\Settings;

use Validator;
use Illuminate\Http\Request;
use App\Models\Settings\NewsSubscriber;
use App\Http\Controllers\Api\AbstractController;
use App\Repositories\Settings\EventRepository;
use App\Support\Transformers\Settings\EventTransformer;
use Illuminate\Http\JsonResponse;

class EventController extends AbstractController
{

    /**
     * Users.
     *
     * @var     EventRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $event;

    /**
     * User controller constructor.
     *
     * @param EventRepository $event
     */
    public function __construct(EventRepository $event){
        parent::__construct();
        $this->event = $event;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->is_article)){
            $event = $this->event->with("getMedia")->with("medias")->orderBy("event.order","ASC")
            ->where('is_article', $request->is_article)
            ->get();
        } else {
            $event = $this->event->with("getMedia")->with("medias")->orderBy("event.order","ASC")
            ->get();
        }
        return $this->respondWithItems($event, new EventTransformer());
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id){
        $event = $this->event->with("getMedia")->with("medias")->retrieveById($id);
        return $this->respondWithItem($event, new EventTransformer());
    }

    public function newsSubscription(Request $request){
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'email' => ['required','email','unique:news_subscribers'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        $newsSubscriber = new NewsSubscriber;
        $newsSubscriber->email = $data['email'];
        $newsSubscriber->save();
        return  response()->json(['error' => false, 'message' => 'email successfully added']);
    }

    public function newsUnsubscription(Request $request){
        $data = $request->getContent();
        if ($data){
            $data = json_decode($data, 1);
        } else {
            $data = $request->all();
        }
        $isValid = Validator::make($data, [
            'email' => ['required','email'],
        ]);

        if ($isValid->fails()){
            return  response()->json(['error' => true, 'message' => $isValid->failed()]);
        }

        NewsSubscriber::where('email', $data['email'])->delete();

        return  response()->json(['error' => false, 'message' => 'email successfully removed']);
    }
}
