<?php

namespace net\bourelly\core\dispatcher;

use com\evemyadmin\controller\AController;
use net\bourelly\core\controller\common\errors\HttpErrorsController;
use net\bourelly\core\utils\handler\CookieHandler;
use net\bourelly\core\view\defaults\ErrorView;
use net\bourelly\core\view\View;

/**
 * Define a dispatcher: a way to respond correctly to the user
 *
 * @package net\bourelly\core\dispatcher
 */
abstract class ADispatcher {

	// Default controller
	const DEFAULT_CONTROLLER = "index";

	/**
	 * Asked page name
	 * @var string the page name
	 */
	protected $page;

	/**
	 * Current AController
	 * @var AController the controller
	 */
	protected $controller;

	/**
	 * Instance of the dispatcher
	 * @var ADispatcher the current dispatcher for the current request
	 * @static
	 */
	private static $INSTANCE = NULL;

	/**
	 * ADispatcher constructor.
	 *
	 * @param string $page the page name
	 */
	protected function __construct(string $page) {
		$this->page = $page;
		$this->controller = AController::getInstance(
				$page,
				str_replace(
						"-",
						"_",
						strtolower(getValueInArrayOrDefault("action", $_GET, AController::DEFAULT_ACTION))
				)
		);
	}

	/**
	 * Retrieves the instance of the dispatcher
	 *
	 * @return ADispatcher instance of the right dispatcher (PHP, AJAX, other)
	 */
	public static function getInstance(): ADispatcher {
		if (!is_null(self::$INSTANCE)) {
			return self::$INSTANCE;
		}

		$askedPage = str_replace(
				"-",
				"_",
				strtolower(getValueInArrayOrDefault("page", $_GET, ADispatcher::DEFAULT_CONTROLLER))
		);
		// If the content type is an AJAX request
		$xRequestedWith = getValueInArrayOrDefault(
				"HTTP_X_REQUESTED_WITH",
				$_SERVER,
				NULL
		);
		if (strcasecmp($xRequestedWith, "xmlhttprequest") == 0) {
			self::$INSTANCE = new AJAX($askedPage);
		} else {
			self::$INSTANCE = new PHP($askedPage);
		}
		return self::$INSTANCE;
	}

	/**
	 * Dispatches the page, the action, and parameters to the
	 * right AController.
	 *
	 * @return View the view to print
	 */
	public final function dispatch(): View {
		if (!is_null($this->controller)) {
			$view = $this->handleResponse();

			// Sets the current URI (if not an error) in the cookie in case of callback redirection
			if (!($view instanceof ErrorView)) {
				CookieHandler::setCookie(
						CookieHandler::ACTIVE_URI,
						getValueInArrayOrDefault(
								"REQUEST_URI",
								$_SERVER,
								"/"
						)
				);
			}

			return $view;
		}
		// Modify the Controller to the error one and handle the request here.
		$this->controller = new HttpErrorsController();
		return $this->handleResponse();
	}

	/**
	 * Merge GET and POST values into one single array.<br>
	 * Also removes value from page and action used by ADispatcher and AController
	 *
	 * @return array every values
	 */
	protected final function getParameters(): array {
		$values = array_merge_recursive(array(), $_GET, $_POST);
		if (array_key_exists("page", $values)) {
			unset($values['page']);
		}
		if (array_key_exists("action", $values)) {
			unset($values['action']);
		}

		$params = getValueInArrayOrDefault("params", $values, NULL);
		if (is_string($params) && !empty($params)) {
			unset($values['params']);
			foreach (explode("/", $params) as $param) {
				if (empty($param)) {
					continue;
				}
				$values[] = $param;
			}
		}
		return $values;
	}

	/**
	 * Handles the response accordingly.
	 *
	 * @return View the view to print
	 */
	protected abstract function handleResponse(): View;

	/**
	 * Prints the current instance of ADispatcher
	 *
	 * @return string the current ADispatcher class name
	 */
	public final function __toString(): string {
		return get_class(self::$INSTANCE);
	}

}
