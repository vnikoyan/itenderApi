<?php

namespace App\Models\Categories;

use Illuminate\Notifications\Notifiable;
use App\Models\AbstractModel;

class Categories extends AbstractModel
{
    protected $table = 'categories';

    protected $allowed = ['name','parent','number','order','children_count', 'children'];
    protected $default = ['name','parent','number','order','children_count', 'children'];

    protected $fillable = ['name','parent','children_count'];

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

    protected $dates = [
	    'created_at',
	    'updated_at',
	    'deleted_at'
	];

	protected $aliases = ['categories'];

    public function getParentIdName()
    {
        return 'parent';
    }
    public function getType()
    {
        if($this->parent !==0){
            $parent = Categories::find($this->parent);
            if($parent->parent !==0){
                $parent = Categories::find($parent->parent);
                if($parent->parent !==0){
                    $parent = Categories::find($parent->parent);
                }
                else {
                    return $parent->id;
                }
            }
            else {
                return $parent->id;
            }
        }else {
            return $this->id;
        }
    }
    public function childrenOne(){
        return $this->hasMany('App\Models\Categories\Categories', 'parent');
    }
    public function children(){
        return $this->hasMany('App\Models\Categories\Categories', 'parent')->with('children');
    }
    public function parent(){
        return $this->hasOne('App\Models\Categories\Categories',"id", 'parent')->with("parent");
    }

}
