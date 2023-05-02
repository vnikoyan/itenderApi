<?php


namespace App\Support\Transformers\Contract;

use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\AbstractTransformer;

class ContractLotsTransformer extends AbstractTransformer
{

    /**
     * User ID. ContractLotsTransformer
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
            case 'is_agency_agreement':
            case 'is_cooperation':
            case 'organize_id':
                
            default:
                return null;
        }

    }
}