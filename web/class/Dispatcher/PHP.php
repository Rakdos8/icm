<?php

namespace Dispatcher;

use Utils\Utils;

/**
 * Dispatcher for the PHP
 */
final class PHP extends ADispatcher {

	protected final function handleResponse($controllerStatus) {
		$templateView = PATH_TEMPLATES . "/" . $this->page . "/" . $this->action . "." . $controllerStatus . ".php";

		// No template found ?
		if (!is_file($templateView)) {
			// Log that the template was not found
			Utils::callStack();
			Utils::log("Template " . $templateView . " does not exist !", time());
			Utils::redirect("/errors/404");
		} else {
			// Retrieves values from AController
			$controllerValues = $this->controller->getTemplateValues();
			if (!empty($controllerValues) && count($controllerValues) == 1) {
				$controllerValues = array_shift($controllerValues);
			}

			$this->values = array_merge($this->values, $controllerValues);
			unset($getValues);
			unset($controllerValues);
		}

		// The template will be the only one which can retrieve data
		ob_start("ob_gzhandler");
		require $templateView;
		$ret = ob_get_clean();
		ob_end_flush();
		return $ret;
	}

}
