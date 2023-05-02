<?php


namespace App\Services\Suggestions;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Models\Suggestions\Suggestions;

use Exception;

class SuggestionsService
{
    use DispatchesJobs;

    /**
     * Incoming HTTP Request.
     *
     * @var Request;
     */
    protected $request;
    /** * User Service Class Constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request){
        $this->request = $request;
    }

    public function createSuggestion(){
        $data = $this->request->all();
        $suggestion = new Suggestions();
        $suggestion->client_id =  $data['client_id'];
        $suggestion->provider_id =  $data['provider_id'];
        $suggestion->organize_id =  $data['organize_id'];
        $suggestion->is_signature =  $data['is_signature'];
        $suggestion->responded =  $data['responded'];
        $suggestion->is_itender = $data['is_itender'];
        $suggestion->save();
    }

    public function createSuggestions(){
        $suggestions = $this->request->all();
        Suggestions::insert($suggestions);
    }

    public function updateSuggestion($id):Suggestions{
        $suggestion = Suggestions::findOrFail($id);
        return $this->builder($suggestion);
    }

    public function uploadAdditionalFile($id):Suggestions{
        $suggestion = Suggestions::findOrFail($id);
        if(!empty($this->request->file('file'))){
            $value = $this->request->file('file');
            $filenameWithExt = $value->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $value->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $path = $value->storeAs('/tender/suggestion_file',$fileNameToStore,"publicP");
            $fileURL = config('app.url')."/uploads/".$path;
            $suggestion->additional_file = $fileURL;
            $suggestion->save();
        }
        return $suggestion;
    }

    public function deleteAdditionalFile($id):Suggestions{
        $suggestion = Suggestions::findOrFail($id);
        $suggestion->additional_file = null;
        $suggestion->save();
        return $suggestion;
    }
    
    private function builder(Suggestions $suggestion) {
        $data = $this->request->all();
        foreach ($data as $key => $value){
            try {
                $suggestion->{$key} = $value;
            } catch (Exception $e) {
                return false;
            }
        }
        $suggestion->save();
        return $suggestion;

    }

}
