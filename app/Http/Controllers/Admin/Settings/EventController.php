<?php
namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AbstractController;
use App\Models\Settings\Event;
use App\Models\Settings\EventMedia;
use App\Models\Translation\Language;
use App\Models\Settings\NewsSubscriber;
use Yajra\Datatables\Datatables;
use App\Jobs\ProcessNewsEmailNotification;
use App\Http\Requests\Settings\Event\EventStoreAndUpdateRequest;
use App\Http\Requests\Settings\Event\EventStoreRequest;
use App\Http\Requests\Settings\Event\EventUpdateRequest;
use App\Models\User\User;
use Auth;
use Illuminate\Support\Facades\Config;

class EventController extends AbstractController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){   
        $this->middleware('permission:settings');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        return view('admin.settings.event.index');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Event $event){
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.event.add',compact('event','language'));
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function subscribers(Event $event){
        return view('admin.settings.event.subscribers');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventStoreRequest $request,Event $event){
        foreach($request->title as $key => $value){
            $event->setTranslation('title', $key , $value);
            $event->setTranslation('description', $key , $request->description[$key]);
        }
        $event->order         = $request->order;
        $event->youtube_link  = $request->youtube_link;
        $event->is_article    = (boolean)$request->is_article;
        $event->save();

        if(!empty($request->file('image'))){
            foreach($request->file('image') as $key => $value){
                $filenameWithExt = $value->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $value->getClientOriginalExtension();
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                $path = $value->storeAs('/event',$fileNameToStore,"publicP");
                $eventMedia = new EventMedia();
                $eventMedia->event_id = $event->id;
                $eventMedia->media = $fileNameToStore;
                $eventMedia->type = 1;
                $eventMedia->save();
            }
        }
        if(!empty($request->file('video'))){
                
                $value = $request->file('video');
                $filenameWithExt = $value->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $value->getClientOriginalExtension();
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                $path =  $value->storeAs('/event',$fileNameToStore,"publicP");

                $eventMedia = new EventMedia();
                $eventMedia->event_id = $event->id;
                $eventMedia->media = $fileNameToStore;
                $eventMedia->type = 2;
                $eventMedia->save();
        }

        $users_email = [];
        $users = NewsSubscriber::get();
        foreach($users as $user){
            $users_email[] = $user->email;
        }
        $users = User::where('type', 'USER')->get();
        foreach($users as $user){
            $users_email[] = $user->email;
        }
        $users_email = array_unique($users_email);
        $data = new \stdClass();
        $url = Config::get('values')['frontend_url'].'/new/'.$event->id;



        $data->subject = $request->title['hy'];

        foreach($users_email as $email){
            $unsubscribe_url = Config::get('values')['frontend_url'].'/user/unsubscribe/'.$email;
            $data->text = " <p>Հրապարակումը կարող եք դիտել հետևյալ հղմամբ` <a href = '".$url."'>Տեսնել հրապարակումը</a> </p></br>
                <p>Հարգանքով՝ iTender թիմ</p>
                <p></p>
                <p style='padding-top:100px'>
                    <a style='color:#333333;font-size:12px;line-height:16px;font-family:Segoe UI,Arian AMU,sans-serif;text-decoration: none;' href = '".$unsubscribe_url."'>Ապաբաժանորդագրվել</a>
                </p>";
            $data->email = trim($email);
            ProcessNewsEmailNotification::dispatch($data);
        }
        
        return redirect("/admin/event");
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id){
        $event = Event::with("medias")->findOrFail($id);
        $language = Language::getLanguages(Language::STATUS_ACTIVE);
        return view('admin.settings.event.edit',compact('event','language'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function update(EventUpdateRequest $request,$id){
        $event = Event::findOrFail($id);
        foreach($request->title as $key => $value){
            $event->setTranslation('title', $key , $value);
            $event->setTranslation('description', $key , $request->description[$key]);
        }
        $event->order         = $request->order;
        $event->youtube_link  = $request->youtube_link;
        $event->is_article    = (boolean)$request->is_article;
        $event->save();

        if(!empty($request->file('image'))){
            foreach($request->file('image') as $key => $value){
                $filenameWithExt = $value->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $value->getClientOriginalExtension();
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                $path = $value->storeAs('/event',$fileNameToStore,"publicP");
                $eventMedia = new EventMedia();
                $eventMedia->event_id = $event->id;
                $eventMedia->media = $fileNameToStore;
                $eventMedia->type = 1;
                $eventMedia->save();
            }
        }
        if(!empty($request->file('video'))){
            
            EventMedia::where("type","2")->where("event_id",$event->id)->delete();

            $value = $request->file('video');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path =  $value->storeAs('/event',$fileNameToStore,"publicP");

            $eventMedia = new EventMedia();
            $eventMedia->event_id = $event->id;
            $eventMedia->media = $fileNameToStore;
            $eventMedia->type = 2;
            $eventMedia->save();
    }
        
        return redirect("/admin/event");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function imageDelete($id){
        EventMedia::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }

    public function destroy($id){
        Event::findOrFail($id)->delete();
        return json_encode(["status"=>true]);
    }
     /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tableData(){
        $tableData =  Datatables::of(Event::with("getMedia")->orderBy('order')->select('title','id',"order"));
        return $tableData->addColumn('action', function ($event) {
                     return '<a href="/admin/event/'.$event->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>  
                             <a href="#" data-tableName="userTable" data-href="/admin/event/delete/'.$event->id.'"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>';
                 })->addColumn('title', function ($event) {
                    return $event->title;
                 })->addColumn('image', function ($event) {
                     if($event->getMedia){
                        return $event->getMedia->media;
                     }
                     return "";
                 })->filterColumn('title', function($query, $keyword) {
                    $query->where("name->en", $keyword);
                })
        ->make(true);
    }

    public function subscribersTableData(){
        $tableData =  Datatables::of(NewsSubscriber::orderBy('id', 'DESC')->get());
        return $tableData->make(true);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function fileDownload($id){
        // return \Storage::download("public/event/".Event::findOrFail($id)->image);
    }
}