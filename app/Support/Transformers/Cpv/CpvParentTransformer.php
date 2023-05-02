<?php

// Define the namespace
namespace App\Support\Transformers\Cpv;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;



class CpvParentTransformer extends AbstractTransformer
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
				return (int) $item->{$field};
			case 'name':
				return (string) $item->{$field};
			case 'code':
				return (string) $item->{$field};
			case 'type':
				return (string) $item->{$field};
			case 'parent':
				$transformer = new CpvParentTransformer();
				return $transformer->collection($item->parent) ? $transformer->collection($item->parent)[0] : null;
			default:
				return null;

		}
	}
}
