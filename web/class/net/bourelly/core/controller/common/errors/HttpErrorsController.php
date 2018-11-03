<?php

namespace net\bourelly\core\controller\common\errors;

use com\evemyadmin\controller\AController;
use net\bourelly\core\controller\common\errors\views\Error403;
use net\bourelly\core\controller\common\errors\views\Error404;
use net\bourelly\core\controller\common\errors\views\Error501;
use net\bourelly\core\view\defaults\ErrorView;
use net\bourelly\core\view\View;

/**
 * Handles the HTTP Errors pages
 *
 * @package net\bourelly\core\controller\common\errors
 */
final class HttpErrorsController extends AController {

	public function execute(array $params = array()): View {
		$action = getValueInArrayOrDefault("action", $_GET, "404");

		if (strcmp($action, "403") == 0) {
			return new Error403();
		} else if (strcmp($action, "404") == 0) {
			return new Error404();
		} else if (strcmp($action, "501") == 0) {
			return new Error501();
		}
		return new ErrorView(ErrorView::DEFAULT_ERROR . " (" . $action . ")");
	}

}
