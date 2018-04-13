<?php

namespace Dispatcher;

use Utils\Utils;

/**
 * Dispatcher for the PHP
 */
final class PHP extends ADispatcher {

	protected final function handleResponse($controllerStatus) {
		$templateView = PATH_TEMPLATES . "/" . $this->page . "/" . $this->action . "." . $controllerStatus . ".php";
		unset($controllerStatus);

		// No template found ?
		if (!is_file($templateView)) {
			// Log that the template was not found
			Utils::callStack();
			Utils::log("Template " . $templateView . " does not exist !", time());
			Utils::redirect("/errors/404");
			die;
		}

		// Retrieves values from the Controller
		$controllerValues = $this->controller->getTemplateValues();
		// Shift the array if only 1 dimension
		if (!empty($controllerValues) && count($controllerValues) == 1) {
			$controllerValues = array_shift($controllerValues);
		}

		$values = array_merge($this->values, $controllerValues);
		unset($getValues);
		unset($controllerValues);

		// The template will be the only one which can retrieve data
		ob_start(
			!in_array("ob_gzhandler", ob_list_handlers()) ?
				"ob_gzhandler" : NULL
		);
		require $templateView;
		$ret = ob_get_clean();
		ob_end_flush();
		return $ret;
	}

}
