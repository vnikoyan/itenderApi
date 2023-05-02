<?php

namespace App\Support\Transformers\PurchasingProcess;

use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\AbstractTransformer;

class PurchasingProcessTransformer extends AbstractTransformer
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
     * @param TransformableModelInterface $item
     * @param   $field
     * @return  mixed
     */
    public function map(TransformableModelInterface $item, $field)
    {
        switch ($field) {
            case 'id':
            case 'procurement_plan_id':
            case 'organize_id':
            case 'count':
            case 'code':
            case 'address':
            case 'other_requirements':
            case 'is_full_decide':
            case 'is_all_participants':
            case 'deadline':
                return $item->{$field};
            case 'participants':
                return $item->{$field};
            default:
                return null;
        }

    }
}
