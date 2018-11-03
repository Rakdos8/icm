<?php

namespace net\bourelly\core\model\expression\where;

use net\bourelly\core\model\expression\SqlExpression;

/**
 * SQLException for IsTrue
 *
 * @package net\bourelly\core\model\expression\where
 */
class IsTrue extends SqlExpression {

	/**
	 * @return string the SQL query
	 */
	public function toSql(): string {
		return "1 = 1";
	}

}
