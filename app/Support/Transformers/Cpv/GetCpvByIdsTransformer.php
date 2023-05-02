<?php


namespace App\Support\Transformers\Cpv;


use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\AbstractTransformer;
use App\Support\Transformers\Settings\ClassifierTransformer;

class GetCpvByIdsTransformer extends AbstractTransformer
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
            case 'name':
                return $item->name;
            case 'code':
                return $item->code;
            case 'type':
                return $item->type;
            case 'unit':
                return $item->unit;
            case 'classifier_cpv':
                return $item->classifierCpv;
            case 'specifications':
                $transformer = new CpvSpecificationTransformer();
                return $transformer->collection($item->specifications);
            default:
                return null;

        }
    }
}
