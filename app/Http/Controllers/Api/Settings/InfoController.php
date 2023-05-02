<?php


namespace App\Http\Controllers\Api\Settings;


use App\Http\Controllers\Api\AbstractController;
use App\Repositories\Settings\InfoRepository;
use App\Support\Transformers\Settings\InfoTransformer;
use Illuminate\Http\JsonResponse;
use App\Models\Tender\TenderState;
use App\Models\User\User;
use App\Models\User\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;

class InfoController extends AbstractController
{
    /**
     * Users.
     *
     * @var     InfoRepository
     * @access  protected
     * @since   1.0.0
     */
    protected $info;

    /**
     * User controller constructor.
     *
     * @param InfoRepository $info
     */
    public function __construct(InfoRepository $info){
        parent::__construct();
        $this->info = $info;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $info = $this->info->orderBy('order', 'ASC')->get();
        return $this->respondWithItems($info, new InfoTransformer());
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id){
        $info = $this->info->retrieveById($id);
        return $this->respondWithItem($info, new InfoTransformer());
    }

    public function info(){
        $data = array();
        $data['active_tenders'] = TenderState::select("tender_state.*")->where("is_competition","=",1)->where('end_date','>',date("Y-m-d H:i:s"))->count();
        $data['today_tenders'] = TenderState::select("tender_state.*")->where("is_competition","=",1)->where("created_at","LIKE","%".date("Y-m-d")."%")->count();
        $archive_count = DB::select('select count(id) as count from tender_state_archive');
        $data['all_tenders'] = $archive_count[0]->count + TenderState::where("is_competition",1)->count();
        $data['users'] = User::where("type","USER")->count();
        $data['users_state'] = Organisation::whereHas('userNoG', function($q){
                $q->where('type', 'STATE');
            })->count();

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
