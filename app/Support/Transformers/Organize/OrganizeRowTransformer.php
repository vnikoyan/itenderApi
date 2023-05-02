<?php

// Define the namespace
namespace App\Support\Transformers\Organize;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;

class OrganizeRowTransformer extends AbstractTransformer
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
    public function map($item, $field)
    {
        switch ($field) {
            case 'id':
                return (int) $item->{$field};
            case 'count':
            case 'supply':
            case 'supply_date':
            case 'is_main_tool':
            case 'done_negotiations':
            case 'is_collateral_requirement':
            case 'is_product_info':
            case 'winner_lot_trademark':
            case 'winner_lot_brand':
            case 'winner_lot_manufacturer':
            case 'is_from_outside':
            case 'financial_classifier_id':
            case 'classifier_id':
            case 'financial_classifier':
            case 'classifier':
            case 'unit_amount':
            case 'type':
            case 'is_condition':
            case 'condition_type':
            case 'view_id':
            case 'unit':
            case 'cpv_type':
            case 'cpv_id':
                return  $item->{$field};
            case 'cpv_name':
            case 'cpv_code':
            case 'cpv_drop':
                return  $item->{$field};
            case 'participantsList':
                return  $item->participantsList();
            case 'shipping_address':
                return  $item->shipping_address;
            case 'winner_lot_specification':
                return  json_decode($item->winner_lot_specification);
            case 'plan_specifications':
                return  json_decode($item->plan_specifications);
            default:
                return  $item->{$field};
        }
    }
}


