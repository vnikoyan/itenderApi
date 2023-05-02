<?php

// Define the namespace
namespace App\Support\Transformers\Cpv;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;



class CpvChildrenTransformer extends AbstractTransformer
{

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
				return (int) $item->{$field};
			case 'name':
				return (string) $item->{$field};
			case 'name_ru':
			case 'unit_ru':
			case 'unit':
				return (string) $item->{$field};
			case 'code':
				return (string) $item->{$field};
			case 'type':
				return (string) $item->{$field};
			case 'children_count':
				return count($item->childrenOne);
			case 'statistics_count':
				return count($item->statistics);
			case 'parent':
				$transformer = new CpvParentTransformer();
				return $transformer->collection($item->parent) ? $transformer->collection($item->parent)[0] : null;
			default:
				return null;

		}
	}
}
