<?php

// Define the namespace
namespace App\Support\Transformers\Category;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\Category\CategoryChildrenTransformer;


class CategoryTransformer extends AbstractTransformer
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
			case 'name':
				return (string) $item->{$field};
			case 'children_count':
				return count($item->childrenOne);
			case 'children':
				$transformer = new CategoryChildrenTransformer();
				return $transformer->collection($item->childrenOne);
			default:
				return null;

		}
	}
}
