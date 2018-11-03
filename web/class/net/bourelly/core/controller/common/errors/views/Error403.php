<?php

namespace net\bourelly\core\controller\common\errors\views;

use net\bourelly\core\view\defaults\ErrorView;

/**
 * Class Error403 for the show in Errors controller
 *
 * @package net\bourelly\core\controller\common\errors\views
 */
class Error403 extends ErrorView {

	/**
	 * Error403 constructor.
	 */
	public function __construct() {
		parent::__construct("Accès refusé !");
	}

}
