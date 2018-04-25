<?php

namespace Model\Expression\Where;

use Model\Expression\SqlExpression;

/**
 * java.sql.SQLException for GreaterThan
 *
 * @package Model\Expression\Where
 */
class GreaterThan implements SqlExpression {

	/** @var string $column */
	private $column;
	/** @var boolean $orEquals */
	private $orEquals;

	/**
	 * GreaterThan constructor.
	 *
	 * @param string $column the column name
	 * @param boolean $orEquals greater or equals ? false by default
	 */
	public function __construct($column, $orEquals = false) {
		$this->column = $column;
		$this->orEquals = $orEquals;
	}

	/**
	 * @return string the SQL query
	 */
	public function toSql() {
		return $this->column . " " . ($this->orEquals ? ">=" : ">") . " ?";
	}

}