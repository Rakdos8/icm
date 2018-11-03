<?php

namespace net\bourelly\core\model\expression\where;

use net\bourelly\core\model\expression\SqlExpression;

/**
 * SQLException for LessThan
 *
 * @package net\bourelly\core\model\expression\where
 */
class LessThan extends SqlExpression {

	/** @var string $column */
	private $column;
	/** @var bool $orEquals */
	private $orEquals;

	/**
	 * LessThan constructor.
	 *
	 * @param string $column the column name
	 * @param bool $orEquals greater or equals ? false by default
	 */
	public function __construct(string $column, bool $orEquals = false) {
		$this->column = $column;
		$this->orEquals = $orEquals;
	}

	/**
	 * @return string the SQL query
	 */
	public function toSql(): string {
		return $this->column . " " . ($this->orEquals ? "<=" : "<") . " ?";
	}

}
