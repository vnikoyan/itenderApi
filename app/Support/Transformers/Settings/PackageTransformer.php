<?php


namespace App\Support\Transformers\Settings;


use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\AbstractTransformer;

class PackageTransformer extends AbstractTransformer
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
                return (string) $item->{$field};
            case 'price':
                $transformer["price_1"] = [
                    "month"=>1,
                    "price"=> $item->price_1,
                ];
                $transformer["price_3"] = [
                    "month"=>3,
                    "price"=> $item->price_3,
                ];
                $transformer["price_6"] = [
                    "month"=>6,
                    "price"=> $item->price_6,
                ];
                $transformer["price_12"] = [
                    "month"=>12,
                    "price"=> $item->price_12,
                ];
                return $transformer;
            default:
                return null;
        }

    }
}
