<?php

namespace net\bourelly\core\controller\common\errors\views;

use net\bourelly\core\view\defaults\ErrorView;

/**
 * Class Error404 for the show in Errors controller
 *
 * @package net\bourelly\core\controller\common\errors\views
 */
class Error404 extends ErrorView {

	/**
	 * Error404 constructor.
	 */
	public function __construct() {
		parent::__construct("La ressource demandée n'existe pas.");
	}

}
