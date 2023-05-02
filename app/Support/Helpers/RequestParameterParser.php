<?php

// Define the namespace
namespace App\Support\Helpers;

// Include any required classes, interfaces etc...
use App\Support\Contracts\ParameterParserInterface;

class RequestParameterParser implements ParameterParserInterface
{
	/**
	 * Fields requested.
	 *
	 * @var string
	 */
	protected $parameters;

	/**
	 * Table.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * Primary key.
	 *
	 * @var string
	 */
	protected $primary;

	/**
	 * Default fields returned response.
	 *
	 * @var array
	 */
	protected $default = [];

	/**
	 * Allowed fields in response.
	 *
	 * @var array
	 */
	protected $allowed = [];

	/**
	 * Fields that must be returned.
	 *
	 * @var array
	 */
	protected $dependant = [];

	/**
	 * Aliases for the requested resource. Used to identify the fields requested.
	 *
	 * @var array
	 */
	protected $aliases = [];
	
	/**
	 * Request parameter parser constructor.
	 *
	 * @param string $parameters
	 * @param string $table
	 * @param array $default
	 * @param array $allowed
	 * @param array $dependant
	 * @param array $aliases
	 * @param string $primary
	 */
	public function __construct($parameters, $table, array $default, array $allowed, array $dependant, array $aliases, $primary = 'id')
	{
		$this->parameters = $parameters;
		$this->table = $table;
		$this->default = $default;
		$this->allowed = $allowed;
		$this->dependant = $dependant;
		$this->aliases = $aliases;
		$this->primary = $primary;
	}

	/**
	 * Filters the requested fields and returns a list of the allowed ones.
	 *
	 * @param array $fields
	 * @return array
	 */
	public function getAccessibleFields(array $fields = [])
	{
		$params = $this->extractParams();

		$columns = $fields ? array_merge($fields, $params) : $params;

		if (empty($columns)) {
			$columns = $this->default;
		}

		$columns = $this->substituteShortCodes($columns);

		$columns = array_intersect($columns, $this->allowed);
		array_unshift($columns, $this->primary);

		return $columns;
	}

	/**
	 * Returns a list of fields for a SELECT query.
	 * The list contains only the required for the response fields.
	 *
	 * @param array $fields
	 * @return array
	 */
	public function getSelectionFields(array $fields = [])
	{
		$columns = $this->getAccessibleFields($fields);

		foreach ($this->dependant as $key => $dependency) {
			$position = array_search($key, $columns);

			if (false === $position) {
				continue;
			}

			unset($columns[$position]);

			if ($dependency) {
				$columns = array_merge($columns, $dependency);
			}
		}
		
		foreach ($columns as $key => $column) {
			$columns[$key] = $this->table.'.'.$column;
		}

		return $columns;
	}

	/**
	 * Extracts the requested parameters from the incoming request.
	 *
	 * @return array
	 */
	protected function extractParams()
	{
		if (!$this->aliases || !$this->parameters) {
			return [];
		}

		preg_match('#(' . implode('|', $this->aliases) . ')\{(.*?)\}#', $this->parameters, $matches);

		if (!isset($matches[2])) {
			return [];
		}

		return explode(',', $matches[2]);
	}

	/**
	 * Substitutes the incoming short codes for the corresponding fields.
	 *
	 * @param array $columns
	 * @return array
	 */
	protected function substituteShortCodes(array $columns)
	{
		if (in_array(':all', $columns)) {
			return $this->allowed;
		}

		if (in_array(':default', $columns)) {
			$columns = array_merge($this->default, $columns);
		}

		return $columns;
	}
}