<?php

// Define the namespace
namespace App\Support\Transformers\Organize;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;
use SplFixedArray;

class OrganizeCardTransformer extends AbstractTransformer
{
	/**
	 * User ID.
	 * OrganizeRowTransformer
	 * @var     integer
	 * @access  protected
	 * @since   1.0.0
	 */
	protected $user_id;

    /**
     * User transformer constructor.
     *
     * @param null $user_id
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
			case 'translations':
				return (array) $item->translations;
			case 'confirm':
				return (boolean) $item->confirm;
			case 'rights_responsibilities_fulfillment':
				return (boolean) $item->rights_responsibilities_fulfillment;
			case 'create_contract':
				return (boolean) $item->create_contract;
			case 'done_negotiations':
				return (boolean) $item->done_negotiations;
			case 'get_invitation':
				return (boolean) $item->get_invitation;
			case 'is_construction':
				return (boolean) $item->is_construction;
			case 'text_approval_date':
			case 'publication':
			case 'decision_number':
            case 'public_date':
            case 'submission_date':
            case 'opening_date':
            case 'opening_time':
            case 'prepayment':
            case 'paper_fee':
            case 'fee':
            case 'account_number':
            case 'prepayment_max':				
			case 'cpv_type':
            case 'prepayment_time':
            case 'organize_type':
				return (string) $item->{$field};
            default:
				return null;
		}

	}
}
