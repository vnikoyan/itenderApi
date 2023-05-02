<?php

// Define the namespace
namespace App\Support\Contracts;


interface TransformerInterface
{
	/**
	 * Transforms a single item.
	 *
	 * @param TransformableModelInterface|null $item
	 * @return mixed
	 */
	public function transform(TransformableModelInterface $item = null);

	/**
	 * Transforms a collection of items.
	 *
	 * @param $items
	 * @return mixed
	 */
	public function collection($items);

	/**
	 * Description of the transformation rules.
	 *
	 * @param TransformableModelInterface $item
	 * @param $field
	 * @return mixed
	 */
	public function map(TransformableModelInterface $item, $field);
}