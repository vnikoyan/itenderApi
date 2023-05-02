<?php


namespace App\Support\Transformers\Settings;


use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\AbstractTransformer;

class InfoTransformer extends AbstractTransformer
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
     * @param integer $user_id
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
            case 'title':
                return  $item->translations["title"];
            case 'description':
                return $item->translations["description"];
            default:
                return null;
        }

    }
}
