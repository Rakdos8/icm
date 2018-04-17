<?php

namespace Controller;

use View\Errors\Error403;
use View\Errors\Error404;
use View\Errors\Error501;
use View\ErrorView;

/**
 * Handles the Errors page
 */
final class Errors extends AController {

	public function show($params = array()) {
		$page = array_key_exists("page", $_GET) && !empty($_GET['page']) ?
			$_GET['page'] :
			"403";
		if (strcmp($page, "403")) {
			return new Error403();
		} else if (strcmp($page, "404")) {
			return new Error404();
		} else if (strcmp($page, "501")) {
			return new Error501();
		}
		return new ErrorView();
	}

}
