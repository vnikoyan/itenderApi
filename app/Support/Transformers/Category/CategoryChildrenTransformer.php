<?php

// Define the namespace
namespace App\Support\Transformers\Category;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;



class CategoryChildrenTransformer extends AbstractTransformer
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
			case 'children_count':
				return count($item->childrenOne);
			default:
				return null;

		}
	}
}
