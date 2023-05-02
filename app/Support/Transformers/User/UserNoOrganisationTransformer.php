<?php

// Define the namespace
namespace App\Support\Transformers\User;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\User\UserOrganisationTransformer;
/**
 * User Transformer
 *
 * @author      Ben Carey <ben@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
class UserNoOrganisationTransformer extends AbstractTransformer
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
	 * @param integer   $user_id
	 * @param array     $fields
	 */
	public function __construct($user_id = null, $fields = [])
	{
		parent::__construct($fields);

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
				return (string) $item->name;
			case 'email':
				return (string) $item->email;
			case 'divisions':
				return (string) $item->divisions;
			case 'phone':
				return (int) $item->phone;
			default:
				return null;

		}
	}
}

