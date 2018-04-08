<?php

namespace Dispatcher;

use Controller\AController;

/**
 * Dispatcher for the AJAX
 */
final class AJAX extends ADispatcher {

	protected final function handleResponse($controllerStatus) {
		$json = array();

		// If the result is OK
		if ($controllerStatus != AController::CONTROLLER_MISSING) {
			$data = $this->controller->getTemplateValues();
			// Removes the first value in the array
			//TODO Which is what ?
			$this->values = array_shift($data);

			// If the controller had an error
			if ($controllerStatus == AController::TREATMENT_ERROR) {
				$json['state'] = "error";
				$json['error'] = $this->values;
			} else {
				$json['state'] = "ok";
				$json['value'] = $this->values;
			}
		} else {
			$json['state'] = "error";
			$json['error'] = "Controller does not exist.";
		}
		// Modify HTML into UTF-8 elements
		array_walk_recursive($json, AController::class . "::htmlentities_array_map");

		// Prints the JSON
		echo json_encode($json, JSON_UNESCAPED_SLASHES);

		// Nothing more required, die "properly"
		die;
	}

}
