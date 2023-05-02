<?php

namespace App\Support\Transformers\Participant;

use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\AbstractTransformer;

class ParticipantRowTransformer extends AbstractTransformer
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
            case 'row_group_id':
            case 'organize_row_id':
            case 'cost':
            case 'profit':
            case 'value':
            case 'new_value':
            case 'vat':
                return $item->{$field};
            default:
                return null;
        }

    }
}
