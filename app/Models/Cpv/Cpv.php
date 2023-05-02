<?php

namespace App\Models\Cpv;

use Illuminate\Notifications\Notifiable;
use App\Models\AbstractModel;
use Illuminate\Support\Facades\Log;

class Cpv extends AbstractModel
{
    use Notifiable;


    protected $allowed = ["code", "name", 'name_ru', "unit", 'type',"parent_id",'children','parent','children_count','statistics_count','used_count','specifications','specifications_count','potential_paper','potential_electronic','classifier_cpv','unit_ru'];
    protected $default = ["code", "name", 'name_ru', 'children', "type",'children_count','statistics_count','used_count','parent','specifications','specifications_count','potential_paper','potential_electronic','classifier_cpv','unit','unit_ru'];


    protected $fillable = ["code", "name", "unit", "type","parent_id"];

    public function getDefault()
    {
        return $this->default;
    }
    public function setDefault(array $default)
    {
         $this->default = $default;
    }

    public function getFillable()
    {
        return $this->fillable;
    }
    public function setFillable(array $fillable)
    {
         $this->fillable = $fillable;
    }


    public function setAllowed(array $allowed)
    {
         $this->allowed = $allowed;
    }
    public function getAllowed()
    {
        return $this->allowed;
    }


	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
    protected $dates = [
	    'created_at',
	    'updated_at',
	    'deleted_at'
	];

	protected $aliases = ['cpv'];


    protected $table = 'cpv';

    public function getParentIdName()
    {
        return 'parent_id';
    }
    public function childrenOne(){
        return $this->hasMany('App\Models\Cpv\Cpv', 'parent_id');
    }
    public function statistics(){
        return $this->hasMany('App\Models\Statistics\CpvStatistics', 'cpv_id');
    }
    public function children(){
        return $this->hasMany('App\Models\Cpv\Cpv', 'parent_id')->with('children');
    }
    public function specifications(){
        return $this->hasMany('App\Models\Cpv\Specifications', 'cpv_id')->with('statistics');
    }
    public function specificationsWithStatistics(){
        return $this->hasMany('App\Models\Cpv\Specifications', 'cpv_id')->with('statistics')->has('statistics');
    }
    public function participants(){
        return $this->hasManyThrough(
            'App\Models\User\User',
            'App\Models\UserCategories\UserCpvs',
            'cpv_id',
            'id',
            'id',
            'user_id'
        );
    }
    public function classifier(){
        return $this->hasMany('App\Models\Settings\ClassifierCpv', "cpv_id")->with('classifier');
    }
    public function classifierCpv(){
        return $this->hasMany('App\Models\Settings\ClassifierCpv', "cpv_id")->with('classifier');
    }

    public function cpvStatistics(){
        return $this->hasMany('App\Models\Statistics\CpvStatistics', "cpv_id");
    }

    public function tenderStateRow(){
        return $this->hasMany('App\Models\Tender\TenderStateCpv', "cpv_id");
    }

    public function users()
    {
        return $this->hasManyThrough(
            'App\Models\User\User',
            'App\Models\UserCategories\UserCpvs',
            'cpv_id', // Foreign key on the UserCpvs table...
            'id', // Foreign key on the User table...
            'id', // Local key on the cpv table...
            'user_id' // Local key on the UserCpvs table...
        );
    }
    public function parent(){
        return $this->hasMany('App\Models\Cpv\Cpv',"id", 'parent_id')->with("parent");
    }

    public function getParents(&$array){
        $cpv_id = $this->id;
        $found_key = array_search($cpv_id, $array);
        if(!$found_key){
            $array[] = $cpv_id;
        }
        $parent = Cpv::find($this->parent_id);
        $parent_found_key = array_search($this->parent_id, $array);
        if($parent && !$parent_found_key){
            $parent->getParents($array);
        }
    }

    public function getChildren(&$array){
        $cpv_id = $this->id;
        $found_key = array_search($cpv_id, $array);
        if(!$found_key){
            $array[] = $cpv_id;
        }
        $children = $this->children;
        if(count($children)){
            foreach ($children as $child) {
                $child_found_key = array_search($child->id, $array);
                if(!$child_found_key){
                    $child->getChildren($array);
                }
            }
        }
    }

    public function getGroup(){
        $parent = $this->parent;
        if(count($parent)){
            $cpvObj = Cpv::find($parent[0]->id);
            return $cpvObj->getGroup();
        } else {
            return $this;
        }
    }
}
