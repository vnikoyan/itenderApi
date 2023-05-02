<?php

// Define the namespace
namespace App\Support\Transformers\User;

// Include any required classes, interfaces etc...
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;
use App\Support\Transformers\User\UserOrganisationTransformer;
use Illuminate\Support\Facades\Log;

/**
 * User Transformer
 *
 * @author      Ben Carey <ben@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
class UserTransformer extends AbstractTransformer
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

	public function getRuField($data, $field){
        $curr_field = json_decode(json_encode($data), true)[$field];
        if($curr_field){
            return isset($curr_field['ru']) ? $curr_field['ru'] : '';
        } else {
            return "";
        }
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


        // foreach($data['name'] as $key => $value){
        //     $user->setTranslation('name', $key , $data['name'][$key]);
        //     $user->setTranslation('company_type', $key , $data['company_type'][$key]);
        //     $user->setTranslation('nickname', $key , $data['nickname'][$key]);
        //     $user->setTranslation('region', $key , $data['region'][$key]);
        //     $user->setTranslation('city', $key , $data['city'][$key]);
        //     $user->setTranslation('address', $key , $data['address'][$key]);
        //     $user->setTranslation('bank_name', $key , $data['bank_name'][$key]);
        //     $user->setTranslation('director_name', $key , $data['director_name'][$key]);
        // }

		switch ($field) {
			case 'id':
				return (int) $item->{$field};
			case 'phone':
				return (string) $item->phone;
			case 'vat_payer_type':
				return (string) $item->vat_payer_type;
			case 'email':
				return (string) $item->email;
			case 'username':
				return (string) $item->username;
			case 'name':
				return (string) $item->name;
			case 'name_ru':
				return (string) $this->getRuField($item, 'name');
			case 'package':
				if($item->type == "STATE"){
					return $item->getCurrentPackageStateName();
				}
				return $item->getCurrentPackageName();
			case 'unseen_suggestions':
				if($item->type == "USER"){
					$count = 0;
					foreach ($item->suggestions as $suggestion) {
						$organize = $suggestion->organize;
						if(!$organize){
							$organize = $suggestion->organizeItender;
						}
						if($organize){
							if($organize->is_canceled === 0){
								if(strtotime($organize->opening_date_time) >= strtotime(date('Y-m-d H:i:s'))){
									$count++;
								}
							}
						}
					}
					return $count;
				}
				return false;
			case 'telegram_id':
				return (string) $item->telegram_id;
			case 'divisions':
				return (string) $item->divisions;
			case 'email_notifications_time':
				return (string) $item->email_notifications_time;
			case 'email_notifications':
				return (boolean) $item->email_notifications;
			case 'telegram_notifications':
				return (boolean) $item->telegram_notifications;
			case 'is_manager':
				return (boolean) $item->is_manager;
			case 'probation':
				return (boolean) $item->probation;
			case 'rights_responsibilities_fulfillment':
				return (boolean) $item->rights_responsibilities_fulfillment;
			case 'organisation':
				$transformer = new UserOrganisationTransformer();
				return $transformer->collection([$item->organisation]);
			default:
				return null;

		}
	}
}
