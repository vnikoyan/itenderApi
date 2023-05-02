<?php

// Define the namespace
namespace App\Support\Transformers\Cpv;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;


class CpvSpecificationTransformer extends AbstractTransformer
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
			case 'description':
				return (string) $item->{$field};
			case 'users_id':
				return (string) $item->{$field};
			case 'statistics':
				return (string) $item->{$field};
			default:
				return null;

		}
	}
}
