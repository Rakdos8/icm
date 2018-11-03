<?php

namespace net\bourelly\core\model\expression\where;

use net\bourelly\core\model\expression\SqlExpression;
use net\bourelly\core\model\MySQL;

/**
 * SQLException for In
 *
 * @package net\bourelly\core\model\expression\where
 */
class In extends SqlExpression {

	/** @var string $column the column name*/
	private $column;

	/** @var array $values the array of question mark for binding*/
	private $values;

	/**
	 * In constructor.
	 *
	 * @param string $column the column name
	 * @param array $values values to match
	 */
	public function __construct(string $column, array $values) {
		$this->column = $column;
		$this->values = MySQL::createBindingArray($values);
	}

	/**
	 * @return string the SQL query
	 */
	public function toSql(): string {
		return $this->column . " IN (" . implode(", ", $this->values) . ")";
	}

}
