<?php

namespace net\bourelly\core\model\expression;

/**
 * Defines that the object is a SQL expression.
 *
 * @package net\bourelly\core\model\expression
 */
abstract class SqlExpression {

	/**
	 * @return string the SQL query
	 */
	public abstract function toSql(): string;

	/**
	 * @return string the result of <code>toSql()</code>
	 */
	public function __toString(): string {
		return $this->toSql();
	}

}
