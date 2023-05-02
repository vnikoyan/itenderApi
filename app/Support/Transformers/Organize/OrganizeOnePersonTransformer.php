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

class OrganizeOnePersonTransformer extends AbstractTransformer
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
			case 'suggestions':
				return $item->suggestions;
			case 'participants':
				$participants = [];
				$participants_list = [];
				foreach ($item->organizeRows as $row) {
					foreach ($row->participants as $participant_row) {
						$participants[] = $participant_row->group;
					}
				}
				$participants = array_unique($participants);
				foreach ($participants as $participant) {
					if($participant){
						$participants_list[] = new OrganizeParticipantResource($participant);
					}
				}
				return $participants_list;
			case 'organize_rows':
					$rows = [];
					foreach ($item->organizeRows->toArray() as $row) {
						$row_item = $row;
						$participants = [];
						foreach ($row_item['participants'] as $participant) {
							if($participant){
								$participant_item = $participant;
								$participant_item['participant_info'] = $participant_item['group'];
								if($participant_item['group']){
									$participant_item['participant_info'] = $participant_item['group'];
								}
								$participants[] = $participant_item;
							}
						}
						$row_item['participants'] = $participants;
						if($row['is_from_outside'] === 1){
							$row_item['cpv'] = $row['procurement_plan']['cpv_outside'];
							$row_item['procurement_plan']['cpv'] = $row['procurement_plan']['cpv_outside'];
						}
						$rows[] = $row_item;
					}
					return $rows;
			case 'lots':
				return $item->lots;
            case 'procurement':
                $transformer = new \stdClass();
                $transformer->id = $item->procurement && $item->procurement->id;
                $transformer->year = $item->procurement && $item->procurement->year;
                return $transformer;
			case 'is_construction':
				return (boolean) $item->is_construction;
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
			case 'winners':
				if(count($item->winners())){
					$winners = [];
					foreach ($item->winners() as $winner) {
						if($winner){
							if(isset($winner['organize_id'])){
								$winners[] = new OrganizeWinnerParticipantResource($winner);
							}
						}
					}
					return $winners;
				} else {
					return $item->winners();
				}
			case 'winner_user_price_word':
				return $item->priceWord($item->winner_user_price);
			case 'is_with_condition':
				return false;
			case 'name':
			case 'name_ru':
			case 'code':
			case 'code_ru':
			case 'shipping_address':
			case 'publication':
			case 'other_requirements':
			case 'winner_user_price':
			case 'calendar_schedule':
			case 'opening_date_time':
			case 'winner_by_lots':
			case 'contract_html_hy':
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
