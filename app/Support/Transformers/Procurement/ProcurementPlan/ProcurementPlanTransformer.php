<?php

// Define the namespace
namespace App\Support\Transformers\Procurement\ProcurementPlan;

use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;

class ProcurementPlanTransformer extends AbstractTransformer
{
	/**
	 * User ID.
	 *
	 * @var     integer
	 * @access  protected
	 * @since   1.0.0
	 */
	protected $user_id;

	/**
	 * User transformer constructor.
	 *
	 * @param integer $user_id
	 */
	public function __construct($user_id = null)
	{
		parent::__construct();
		$this->user_id = $user_id;
	}
	/**
	 * Description of the transformation rules.
	 *
	 * @param   TransformableModelInterface $item
	 * @param   $field
	 * @return  mixed
	 */
	public function map(TransformableModelInterface $item, $field)
	{

		switch ($field) {
			case 'id':
				return (int) $item->{$field};
			case 'cpv':
				$transformer = new \stdClass();
				$transformer->id =   $item->cpv->id;
				$transformer->code = $item->cpv->code;
				$transformer->type = $item->cpv->type;
				$transformer->name = $item->cpv->name;
				return $transformer;
			case 'cpv_drop':
				return  (integer) $item->{$field};
			case 'cpv_type':
				return  (integer) $item->{$field};
			case 'organisation':
				return null;
			case 'procurement':
                $transformer = new \stdClass();
				$transformer->id = $item->procurement->id;
				$transformer->code = $item->procurement->year;
				$transformer->name = $item->procurement->name;
				return $transformer;
			case 'specifications':
				$transformer = new \stdClass();
				if($item->specifications){
				    $transformer->id = $item->specifications->id;
                    $transformer->description = $item->specifications->translations["description"];
                }
				return $transformer;
			case 'name':
				return (string) $item->{$field};
			case 'date':
				return (string) $item->{$field};
			case 'user':
			     $user  = "user_".(auth('api')->user()->divisions-1);
				 return  $item->{$user};
			case 'condition_type':
			case 'is_condition':
				return (string) $item->{$field};
			case 'status':
				return  $item->{$field};
			case 'details':
				return  $item->{$field};
			case 'order_index':
				return  $item->{$field};
			default:
				return null;
		}

	}
}
