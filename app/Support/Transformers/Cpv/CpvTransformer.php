<?php

// Define the namespace
namespace App\Support\Transformers\Cpv;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\Cpv\CpvChildrenTransformer;
use App\Support\Transformers\Cpv\CpvParentTransformer;


class CpvTransformer extends AbstractTransformer
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
			case 'potential_paper':
			case 'potential_electronic':
				return $item->{$field} ? json_decode($item->{$field}) : 0;
			case 'name':
				return (string) $item->{$field};
			case 'name_ru':
			case 'unit_ru':
			case 'unit':
				return (string) $item->{$field};
			case 'unit':
				return (string) $item->{$field};
			case 'code':
				return (string) $item->{$field};
			case 'type':
				return (string) $item->{$field};
			// case 'statistics_count':
			// 	return count($item->cpvStatistics);
			case 'used_count':
				return count($item->tenderStateRow);
			case 'statistics_count':
				return count($item->statistics);
			case 'children_count':
				return count($item->childrenOne);
			case 'parent':
				$transformer = new CpvParentTransformer();
				return $transformer->collection($item->parent) ? $transformer->collection($item->parent)[0] : null;
			case 'children':
				$transformer = new CpvChildrenTransformer();
				return $transformer->collection($item->childrenOne);
			default:
				return null;

		}
	}
}
