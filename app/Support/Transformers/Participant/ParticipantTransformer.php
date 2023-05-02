<?php


namespace App\Support\Transformers\Participant;

use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\AbstractTransformer;

class ParticipantTransformer extends AbstractTransformer
{

    /**
     * User ID. SelectedParticipantTransformer
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
    public function map(TransformableModelInterface $item, $field)
    {

        switch ($field) {
            case 'id':
            case 'group_id':
            case 'organize_id':
            case 'tin':
            case 'email':
            case 'phone':
            case 'date_of_submission':
                return $item->{$field};
            case 'name':
                return  $item->translations['name'];
            case 'address':
                return  $item->translations['address'];
            default:
                return null;
        }

    }
}
