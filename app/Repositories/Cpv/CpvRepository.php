<?php

// Define the namespace
namespace App\Repositories\Cpv;

// Include any required classes, interfaces etc...
use App\Support\Transformers\Cpv\CpvTransformer;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Support\Cerberus\Contracts\AuthProvider;
use App\Models\Cpv\Specifications;


class CpvRepository extends BaseRepository
{
	/**
	 * Returns the name of the model class to be
	 * used by this repository.
	 *
	 * @return string
	 */
	function model()
	{
		return 'App\Models\Cpv\Cpv';
	}

	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $id
	 * @return string
	 */
	function retrieveById($id)
	{
		return $this->find($id);
	}

	function getByTypeRoot($type)
	{
		return $this->select(["id","code","name"])->where([
			"type"=> $type,
			"parent_id"=> 0
		])->orderBy('code',"ASC")->get();
	}


	function getChlidrenById($id)
	{
		return $this->with('childrenOne')->find($id);
	}

	function getSpecificationsByCpvId($id)
	{
		return $this->with('specifications')->find($id);
	}

	function setSpecificationsByCpvId($request, int $id,int $user_id)
	{
		$specifications = new Specifications();
		foreach($request->description as $key => $value){
            $specifications->setTranslation('description', $key , $request->description[$key]);
        }
        $specifications->users_id = $user_id;
        $specifications->cpv_id = $id;
        $specifications->save();

		return $specifications->id;
	}

	function updateSpecificationsByCpvId($request, int $id)
	{
		$specifications = Specifications::findOrFail($id);
		foreach($request->description as $key => $value){
            $specifications->setTranslation('description', $key , $request->description[$key]);
        }
        $specifications->save();

		return $specifications->id;
	}
	


	/**
	 * Retrieves the user based on their id.
	 *
	 * @param  integer $id
	 * @return string
	 */
	function categorytoTreeDescendants($cal_id)
	{
		return $this->orderBy("_lft")->descendantsAndSelf($cal_id)->toTree();
	}
	/**
	 * Search for top users based on the filter.
	 *
	 * @param $filter
	 * @return Builder
	 */
	public function top($filter = '')
	{

	}
    /**
     *
     * Search for top users based on the filter.
     * @return \Illuminate\Http\Response
     */
    public function searchBy($q)
    {
        return $this->where(function ($query) use ($q) {
            $query->orWhere('code', 'like', '%' . $q . '%');
            $query->orWhere('name', 'like', '%' . $q . '%');
        })->paginate();
    }

    /**
     *
     * Search for top users based on the filter.
     * @return \Illuminate\Support\Collection
     */
    public function getCpvByIds($ids)
    {
        return $this->whereIn("id",$ids)->with('specifications')->with('classifierCpv')->get();
    }

	    /**
     *
     * Search for top users based on the filter.
     * @return \Illuminate\Support\Collection
     */
    public function getCpvChildIds($id)
    {
		$user_cpvs_array = [];
		$currCpv = $this->where("id",$id)->first();
		$currCpv->getChildren($user_cpvs_array);

		$user_cpvs_array = array_values(array_unique($user_cpvs_array, SORT_REGULAR));

		$cpvs = $this->whereIn("id",$user_cpvs_array)->with('specifications')->with('classifierCpv')->with('childrenOne')->get();

        return $cpvs;
    }



}
