<?php

// Define the namespace
namespace App\Support\Transformers\User;

// Include any required classes, interfaces etc...
use Carbon\Carbon;
use App\Support\Transformers\AbstractTransformer;
use App\Support\Contracts\TransformableModelInterface;

/**
 * Experience Transformer
 *
 * @author      Ben Carey <ben@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
class ExperienceTransformer extends AbstractTransformer
{
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
			case 'organisation':
			case 'city':
			case 'country':
				return (string) $item->{$field};

			case 'start_date':
			case 'end_date':
				return (string) $item->{$field} ? Carbon::parse($item->{$field})->format('Y-m-d') : null;

			case 'education':
			case 'current_position':
			return (bool) $item->{$field};
			
			default:
				return null;
			
		}
	}
}