<?php


namespace App\Support\Transformers\Contract;

use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\AbstractTransformer;

class ContractsTransformer extends AbstractTransformer
{

    /**
     * User ID. ContractTransformer
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
            case 'sign_date':
            case 'code':
				return $item->{$field};
            case 'organize':
				return $item->organize;
            case 'participant':
                return $item->participant;
            case 'lots':
                $useless_keys_lots = ['contract_id', 'created_at', 'updated_at', 'organize_row_id'];
                foreach ($item->lots as $lot) {
                    $useless_keys_organize_row = ['organize_id', 'created_at', 'updated_at', 'procurement_plan_id', 'procurement_plan_id', 'procurement_plan_id', 'procurement_plan_id'];
                    foreach ($useless_keys_lots as $key) {
                        unset($lot[$key]);
                    }
                }
                return $item->lots;
            default:
                return null;
        }

    }
}
