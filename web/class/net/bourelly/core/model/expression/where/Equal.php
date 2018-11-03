<?php

namespace net\bourelly\core\model\expression\where;

use net\bourelly\core\model\expression\SqlExpression;

/**
 * SQLException for equal
 *
 * @package net\bourelly\core\model\expression\where
 */
class Equal extends SqlExpression {

	private $column;

	/**
	 * Equal constructor.
	 *
	 * @param string $column the column name
	 */
	public function __construct(string $column) {
		$this->column = $column;
	}

	/**
	 * @return string the SQL query
	 */
	public function toSql(): string {
		return $this->column . " = ?";
	}

}
