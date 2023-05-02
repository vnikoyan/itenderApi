<?php

// Define the namespace
namespace App\Support\Transformers\User;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;

class UserOrganisationTransformer extends AbstractTransformer
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
			case 'tin':
				return (string) $item->tin;
			case 'phone':
				return (string) $item->phone;
			case 'bank_account':
				return (string) $item->bank_account;
			case 'balans':
				return (string) $item->bank_account;
			case 'passport_serial_number':
			case 'passport_given_at':
			case 'passport_from':
			case 'passport_valid_until':
			case 'id_card_number':
				return  $item->{$field};
			case 'translations':
				return  (array) $item->getTranslations();
			default:
				return null;
			
		}
	}
}