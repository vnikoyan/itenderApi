<?php

// Define the namespace
namespace App\Repositories\Categories;

// Include any required classes, interfaces etc...
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\Cerberus\Contracts\AuthProvider;
use App\Models\Cpv\Specifications;

class CategoriesRepository extends BaseRepository
{
	function model()
	{
		return 'App\Models\Categories\Categories';
	}

	function retrieveById($id)
	{
		return $this->find($id);
	}

	function getByTypeRoot($type)
	{
		return $this->select(["id","name",'parent'])->where([
			"parent" => $type
		])->get();
	}


	function getChlidrenById($id)
	{
		return $this->with('childrenOne')->find($id);
	}

    public function searchBy($q)
    {
        return $this->doesntHave('childrenOne')->where(function ($query) use ($q) {
            $query->orWhere('name', 'like', '%' . $q . '%');
        })->paginate();
    }
}
