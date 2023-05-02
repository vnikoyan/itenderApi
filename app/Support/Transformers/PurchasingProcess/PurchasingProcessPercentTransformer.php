<?php


namespace App\Support\Transformers\PurchasingProcess;


use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\AbstractTransformer;

class PurchasingProcessPercentTransformer extends AbstractTransformer
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
            case 'purchasing_process_id':
            case 'name':
            case 'month_1':
            case 'month_2':
            case 'month_3':
            case 'month_4':
            case 'month_5':
            case 'month_6':
            case 'month_7':
            case 'month_8':
            case 'month_9':
            case 'month_10':
            case 'month_11':
            case 'month_12':
                return $item->{$field};
            default:
                return null;
        }

    }
}
