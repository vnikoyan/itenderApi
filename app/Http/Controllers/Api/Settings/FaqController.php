<?php


namespace App\Http\Controllers\Api\Settings;


use App\Http\Controllers\Api\AbstractController;
use App\Repositories\Settings\FaqRepository;
use App\Support\Transformers\Settings\InfoTransformer;
use Illuminate\Http\JsonResponse;
use App\Models\Tender\TenderState;
use App\Models\User\User;
use App\Models\User\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaqController extends AbstractController
{
    /**
     * Users.
     *
     * @var     FaqRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $faq;

    /**
     * User controller constructor.
     *
     * @param FaqRepository $faq
     */
    public function __construct(FaqRepository $faq){
        parent::__construct();
        $this->faq = $faq;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faq = $this->faq->orderBy('order', 'ASC')->get();
        return $this->respondWithItems($faq, new InfoTransformer());
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id){
        $faq = $this->faq->retrieveById($id);
        return $this->respondWithItem($faq, new InfoTransformer());
    }

    public function faq(){
        $data = array();
        $data['active_tenders'] = count(TenderState::select("tender_state.*")->join("organizator","tender_state.organizer_id","=","organizator.id")->where("is_competition","=",1)->where('end_date','>',date("Y-m-d H:i:s"))->get());
        $data['today_tenders'] = count(TenderState::select("tender_state.*")->join("organizator","tender_state.organizer_id","=","organizator.id")->where("is_competition","=",1)->where("created_at","LIKE","%".date("Y-m-d")."%")->get());
        $data['all_tenders'] = count(TenderState::where("is_competition",1)->get());
        $data['users'] = count(User::where("type","USER")->get());
        $data['users_state'] = count(Organisation::whereHas('userNoG', function($q){
                $q->where('type', 'STATE');
            })->get());

        return  response()->json($data);  
    }

    public function callback($match) {
        
    }

    public function parseRowsFromExel(Request $request){

        $str = $request->string;
        $pattern = '/"((?:[^"]*(?:\r\n|\n\r|\n|\r))+([^"]|"")*)"/';
        $results = preg_replace_callback(
            $pattern,
            function ($match) {
                $pattern = array('/""/','/\r\n|\n\r|\n|\r/');
                $replace = array('"', ' ');
                $content = preg_replace($pattern, $replace, $match[1]);
                $string = trim(preg_replace('/\s\s+/', ' ', $content));
                return $string;
            },
            $str
        );


        $rowPattern = '/\r\n|\n\r|\n|\r/';

        $data = preg_split($rowPattern, $results, -1, PREG_SPLIT_NO_EMPTY);
        return response()->json($data);
        
    }
}
