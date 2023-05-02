<?php

// Define the namespace
namespace App\Support\Transformers\Organize;

// Include any required classes, interfaces etc...

use App\Http\Resources\Organize\OnePerson\OrganizeWinnerParticipantResource;
use App\Http\Resources\Organize\OnePerson\OrganizeParticipantResource;
use App\Http\Resources\Organize\OnePerson\OrganizeWinnerUserResource;
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;
use App\Models\Organize\OrganizeOnePerson;
use Illuminate\Support\Facades\Log;

class OrganizeCardOnePersonTransformer extends AbstractTransformer
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
			case 'suggestions_count':
				$count = count($item->suggestions->toArray());
				// if($item->tender){
				// 	$count += $item->tender->participants_count;
				// }
				return $count;
			case 'suggestions_responded_count':
				return count($item->suggestionsResponded->toArray());
			case 'translations':
				return (array) $item->translations;
			case 'winner_by_lots':
				return (boolean) $item->winner_by_lots;
			case 'send_to_all_participants':
				return $item->send_to_all_participants;
			case 'publicize':
				return (boolean) $item->publicize;
			case 'confirm':
				return (boolean) $item->confirm;
			case 'create_contract':
				return (boolean) $item->create_contract;
			case 'name':
			case 'name_ru':
			case 'send_date':
			case 'code':
			case 'code_ru':
			case 'shipping_address':
			case 'publication':
			case 'calendar_schedule':
			case 'opening_date_time':
			case 'winner_by_lots':
            case 'publicize':
            case 'decision_number':
            case 'purchase_schedule':
            case 'protocols_copy_number':
            case 'protocol_presentation_deadline':
			case 'work_type':
			case 'least_work_percent':
            case 'cpv_type':
				return (string) $item->{$field};
            default:
				return null;
		}

	}
}
