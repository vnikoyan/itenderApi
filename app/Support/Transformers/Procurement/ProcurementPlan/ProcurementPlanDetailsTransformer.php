<?php


namespace App\Support\Transformers\Procurement\ProcurementPlan;

use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;


class ProcurementPlanDetailsTransformer extends AbstractTransformer
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
			case 'plan_row_id':
			case 'cpv_id':
			case 'cpv_drop':
			case 'cpv_type':
			case 'specifications_id':
			case 'is_condition':
			case 'type':
			case 'classifier_id':
			case 'financial_classifier_id':
			case 'status':
			case 'order_index':
				return  (integer) $item->{$field};
			case 'cpv_name':
			case 'cpv_code':
			case 'unit':
			case 'count':
			case 'date':
			case 'unit_amount':
			case 'condition_type':
			case 'classifier':
			case 'financial_classifier':
			case 'out_count':
			case 'organize_count':
				return   $item->{$field};
			case 'user':
			     $user  = "user_id_".(auth('api')->user()->divisions-1);
				 return  $item->{$user};
			default:
				return null;
		}

	}
}
