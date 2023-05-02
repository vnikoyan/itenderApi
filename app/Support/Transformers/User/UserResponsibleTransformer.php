<?php


namespace App\Support\Transformers\User;

use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;

class UserResponsibleTransformer  extends AbstractTransformer
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
	 * @param integer   $user_id
	 * @param array     $fields
	 */
	public function __construct($user_id = null, $fields = [])
	{
		parent::__construct($fields);

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
			case 'email':
			case 'divisions':
			case 'username':
			case 'phone':
			    return  $item->{$field};
			case 'password':
				return  '******';
			case 'name':
				return $item->translations['name'];
			case 'members':
			    $m = [];
			    foreach ($item->members as $key => $value){
                    $transformer = new \stdClass();
                    $transformer->id = $value->id;
                    $transformer->name = $value->translations['name'];
                    $transformer->position = $value->translations['position'];
                    $m[$key] =  $transformer;
			    }
				return $m;
			case 'position':
				return json_decode($item->position);
			default:
				return null;

		}
	}
}
