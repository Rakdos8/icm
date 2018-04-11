<?php

namespace Controller;

use Utils\Handler\PhpBB;
use Utils\Utils;

/**
 * Handles every Controller from this abstract class.
 */
abstract class AController {

	// Access refused
	const CONTROLLER_FORBIDDEN = "403";
	// Missing Controller
	const CONTROLLER_MISSING = "404";
	// Missing action
	const ACTION_MISSING = "501";
	// Treatment failed
	const TREATMENT_ERROR = "error";
	// Treatment succeed
	const TREATMENT_SUCCEED = "success";
	// Default action on every controller
	const DEFAULT_ACTION = "show";

	/**
	 * Values to give to template
	 * @var array
	 */
	private $values = array();

	/**
	 * Current instance of AController
	 * @var AController
	 * @static
	 */
	private static $INSTANCE = NULL;

	/**
	 * Retrieves the AController that can handle the asked page.
	 *
	 * @param string $page page name
	 * @return AController the right AController or NULL if any found
	 */
	public static final function getInstance($page) {
		// Retrieves the AController if already created
		if (!is_null(self::$INSTANCE)) {
			return self::$INSTANCE;
		}

		// Retrieve the page + upper case the first letter
		$page = ucfirst($page);
		// Retrieves the Controller which match the page
		$controllerFile = PATH_CONTROLLER . "/" . $page . ".php";
		// If the Controller is found, include it
		if (is_file($controllerFile)) {
			// Update the class name with the namespace of the current one: Controller
			$className = __NAMESPACE__ . "\\" . $page;
			self::$INSTANCE = new $className();
		} else {
			// Log that the template was not found
			Utils::callStack();
			Utils::log("Controller " . $controllerFile . " does not exist !", time());
			Utils::redirect("/errors/404");
		}
		return self::$INSTANCE;
	}

	/**
	 * Executes the action and provides the parameters array to treat the needs.
	 *
	 * @param string $action asked action
	 * @param array $params parameters in an array
	 * @return string the AController treatment status
	 */
	public final function executeAction($action = self::DEFAULT_ACTION, $params = array()) {
		if (method_exists($this, $action)) {
			return $this->$action($params);
		}
		return self::ACTION_MISSING;
	}

	/**
	 * @return array template values provided
	 */
	public final function getTemplateValues() {
		return $this->values;
	}

	/**
	 * Default action for any AController
	 *
	 * @param array $params parameters in an array
	 * @return string treatment state
	 */
	public function show($params = array()) {
		return AController::TREATMENT_ERROR;
	}

	/**
	 * @return PhpBB the PhpBB Handler
	 */
	protected function getPhpbbUser() {
		return PhpBB::getInstance();
	}

	/**
	 * Prints the current instance of AController
	 *
	 * @return string the current AController class name
	 */
	public final function __toString() {
		return get_class(self::$INSTANCE);
	}

	/**
	 * When a set is done, will include it in the template values.
	 *
	 * @param string $name name in the array
	 * @param mixed $value value
	 * @return string the old value in the field, NULL if new
	 */
	public final function __set($name, $value) {
		$oldValue = $this->__get($name);
		$this->values[$name] = $value;
		return $oldValue;
	}

	/**
	 * Retrieves the value in the template values.
	 *
	 * @param string $name name in the array
	 * @return mixed the value if exists, null otherwise
	 */
	public final function __get($name) {
		if (array_key_exists($name, $this->values)) {
			return $this->values[$name];
		}
		return NULL;
	}

}
