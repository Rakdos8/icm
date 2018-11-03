<?php

namespace net\bourelly\core\controller\common\errors\views;

use net\bourelly\core\view\defaults\ErrorView;

/**
 * Class Error501 for the show in Errors controller
 *
 * @package net\bourelly\core\controller\common\errors\views
 */
class Error501 extends ErrorView {

	/**
	 * Error501 constructor.
	 */
	public function __construct() {
		parent::__construct("L'action demandée n'est pas encore implémentée.");
	}

}
