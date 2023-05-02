<?php

// Define the namespace
namespace App\Models;

// Include any required classes, interfaces etc...
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Support\Helpers\RequestParameterParser;
use App\Support\Contracts\ParameterParserInterface;
use App\Support\Contracts\TransformableModelInterface;

/**
 * Abstract Model
 *
 */

abstract class AbstractModel extends Model implements TransformableModelInterface
{
	use DispatchesJobs;

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
	 * Map any aliased parameters.
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
	 * Parser of parameters incoming with the request.
	 *
	 * @var ParameterParserInterface
	 */
	protected $parameterParser;

	/**
	 * Abstract model constructor.
	 *
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);


		$this->parameterParser = new RequestParameterParser(
			Input::get('params'),
			$this->table,
			$this->default,
			$this->allowed,
			$this->dependant,
			$this->aliases,
			$this->primaryKey
		);

	}

	/**
	 * Abstract model constructor.
	 *
	 * @param array $attributes
	 */
	public function relodeRequestParameterParser()
	{
		$this->parameterParser = new RequestParameterParser(
			Input::get('params'),
			$this->table,
			$this->default,
			$this->allowed,
			$this->dependant,
			$this->aliases,
			$this->primaryKey
		);
	}



	/**
	 * Scope a query to return any existing records.
	 *
	 * @param   \Illuminate\Database\Eloquent\Builder $query
	 * @return  \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeExisting(Builder $query)
	{
		return $query->whereNull('deleted_at');
	}

	/**
	 * Scope a query to return just the specified column.
	 *
	 * @param   \Illuminate\Database\Eloquent\Builder $query
	 * @param   string $field
	 * @return  \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeMin($query, $field = 'id')
	{
		return $query->select($field);
	}

	/**
	 * Scope a query to return results in descending order.
	 *
	 * @param   \Illuminate\Database\Eloquent\Builder $query
	 * @param   string $field
	 * @return  \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeDesc($query, $field = 'id')
	{
		return $query->orderBy($field, 'desc');
	}

	/**
	 * Scope a query to return only the required fields.
	 *
	 * @param   \Illuminate\Database\Eloquent\Builder $query
	 * @param   array $fields
	 * @return  \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeApi($query, $fields = [])
	{
		$this->setPerPage(self::getLimit());
		return $query->select($this->parameterParser->getSelectionFields($fields));
	}

	/**
	 * Filters the requested fields and returns a list of the allowed ones.
	 *
	 * @param array $fields
	 * @return array
	 */
	public function getAccessibleFields(array $fields = [])
	{
		return $this->parameterParser->getAccessibleFields($fields);
	}

	/**
	 * Retrieves the limit.
	 *
	 * @return integer
	 */
	public static function getLimit()
	{
		return Input::get('limit') ? : 20;
	}

	/**
	 * Retrieves the offset.
	 *
	 * @return integer
	 */
	public static function getOffset()
	{
		return Input::has('offset') ? Input::get('offset') : 0;
	}
}