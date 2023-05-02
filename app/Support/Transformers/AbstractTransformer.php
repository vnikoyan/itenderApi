<?php

// Define the namespace
namespace App\Support\Transformers;

// Include any required classes, interfaces etc...
use App\Support\Contracts\TransformerInterface;
use App\Support\Contracts\TransformableModelInterface;

/**
 * Abstract Transformer
 *
 * @author      Ben Carey <ben@e-man.co.uk>
 * @copyright   2016 Global Intermedia Limited
 * @version     1.0.0
 * @since       Class available since Release 1.0.0
 */
abstract class AbstractTransformer implements TransformerInterface
{
	/**
	 * The fields.
	 *
	 * @var array
	 */
	protected $fields;

	/**
	 * Abstract transformer constructor.
	 *
	 * @param array $fields
	 */
	public function __construct($fields = [])
	{
		$this->fields = $fields;
	}

	/**
	 * Transforms a single item.
	 *
	 * @param TransformableModelInterface|null $item
	 * @return mixed
	 */
	public function transform(TransformableModelInterface $item = null) {

		if (!$item) {
			return null;
		}

		if (!$this->fields) {
			$this->fields = $item->getAccessibleFields();
		}


		$hidden = $item->getHidden();

		$response = [];
		foreach ($this->fields as $field) {
			if (!in_array($field,$hidden)) {
				$response[$field] = $this->map($item, $field);
			}
		}

		return $response;
	}

	/**
	 * Transforms a collection of items.
	 *
	 * @param $items
	 * @return mixed
	 */
	public function collection($items)
	{
		if (!$items) {
			return [];
		}

		$response = [];
		foreach ($items as $item) {
			$response[] = $this->transform($item);
		}

		return $response;
	}

	/**
	 * Description of the transformation rules.
	 *
	 * @param TransformableModelInterface $item
	 * @param $field
	 * @return mixed
	 */
	public abstract function map(TransformableModelInterface $item, $field);
}