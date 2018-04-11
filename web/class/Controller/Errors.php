<?php

namespace Controller;

/**
 * Handles the Errors page
 */
final class Errors extends AController {

	public function show($params = array()) {
		$page = array_key_exists("page", $_GET) && !empty($_GET['page']) ?
			$_GET['page'] :
			"403";
		if (strcmp($page, "403")) {
			return AController::CONTROLLER_FORBIDDEN;
		} else if (strcmp($page, "404")) {
			return AController::CONTROLLER_MISSING;
		} else if (strcmp($page, "501")) {
			return AController::ACTION_MISSING;
		}
		return AController::TREATMENT_ERROR;
	}

}
