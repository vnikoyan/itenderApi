<?php

// Define the namespace
namespace App\Support\Transformers\Organize;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;
use SplFixedArray;

class OrganizeTransformer extends AbstractTransformer
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
            case 'evaluator_member':
                return (array) json_decode($item->{$field});
			case 'organize_rows':
				$rows = [];
				foreach ($item->organizeRows->toArray() as $row) {
					if($row['is_from_outside'] === 1){
						$row_item = $row;
						$row_item['procurement_plan']['cpv'] = $row['procurement_plan']['cpv_outside'];
						$rows[] = $row_item;
					} else {
						$rows[] = $row;
					}
				}
				return $rows;
            case 'procurement':
                $transformer = new \stdClass();
                $transformer->id = $item->procurement && $item->procurement->id;
                $transformer->year = $item->procurement && $item->procurement->year;
                return $transformer;
			case 'is_with_specification':
				return (boolean) $item->is_with_specification;
			case 'locale_negotiations':
				return (boolean) $item->locale_negotiations;
			case 'is_correction':
				return (boolean) $item->is_correction;
			case 'is_negotiations':
				return (boolean) $item->is_negotiations;
			case 'repair_services':
				return (boolean) $item->repair_services;
			case 'is_construction':
				return (boolean) $item->is_construction;
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
			case 'get_evaluation_session':
				return (boolean) $item->get_evaluation_session;
			case 'prepayment_max_text':
				return $item->priceWord($item->prepayment_max);
			case 'condition_type':
				return count($item->organizeRows) && $item->organizeRows[0]->procurementPlan->condition_type;
			case 'is_with_condition':
				return (boolean) count($item->organizeRows) && $item->organizeRows[0]->procurementPlan->is_condition;
			case 'rows_total_price':
				return  $item->rowsTotalPrice();
			case 'text_approval_date':
			case 'publication':
			case 'contract_html_hy':
			case 'contract_html_ru':
			case 'calendar_schedule':
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
			case 'least_work_percent':
			case 'protocols_copy_number':
			case 'protocol_presentation_deadline':
            case 'prepayment_time':
            case 'evaluator_president':
            case 'evaluator_secretary_name':
			case 'evaluator_secretary_position':
            case 'evaluator_secretary_email':
            case 'evaluator_secretary_phone':
            case 'organize_type':
				return (string) $item->{$field};
            default:
				return null;
		}

	}
}
