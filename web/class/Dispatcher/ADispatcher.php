<?php

namespace Dispatcher;

use Controller\AController;
use Controller\Errors;
use Utils\Utils;

require_once PATH_CONTROLLER . "/AController.php";

/**
 * Classe servant de base pour le Répartiteur (non instanciable !).
 * Héritage obligatoire pour l'exploiter
 *
 * @package web.class.repartiteur
 */
abstract class ADispatcher {

	// Types of dispatcher available
	const DISPATCHER_PHP = "PHP";
	const DISPATCHER_AJAX = "AJAX";
	// Default controller
	const DEFAULT_CONTROLLER = "Index";

	/**
	 * Asked page name
	 * @var string
	 */
	protected $page;

	/**
	 * Asked action name
	 * @var string
	 */
	protected $action;

	/**
	 * Current AController
	 * @var \Controller\AController
	 */
	protected $controller;

	/**
	 * Values for template
	 * @var array
	 */
	protected $values;

	/**
	 * Instance of the dispatcher
	 * @var ADispatcher
	 * @static
	 */
	private static $INSTANCE = NULL;

	/**
	 * Retrieves the instance of the Dispatcher
	 *
	 * @return ADispatcher instance of the right Dispatcher (PHP, AJAX, other)
	 */
	public static function getInstance() {
		if (!is_null(self::$INSTANCE)) {
			return self::$INSTANCE;
		}

		$page = self::getPage();
		self::$INSTANCE = self::getDispatcherType($page);
		self::$INSTANCE->page = $page;
		self::$INSTANCE->controller = AController::getInstance($page);
		return self::$INSTANCE;
	}

	/**
	 * Retrieves the required type of Dispatcher according to the
	 * given page name.
	 *
	 * @param string $page name of the page
	 * @return ADispatcher the required dispatcher for the page
	 */
	private static final function getDispatcherType($page) {
		// List here every AJAX pages
		$pagesAJAX = array();

		// If the page is in the array
		if (in_array($page, $pagesAJAX)) {
			return new AJAX();
		}
		return new PHP();
	}

	/**
	 * Retrieves the page from the $_GET.<br>
	 * Also replaces "-" into "_".
	 *
	 * @return string the asked page
	 */
	private static final function getPage() {
		if (array_key_exists("page", $_GET) && !empty($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = ADispatcher::DEFAULT_CONTROLLER;
		}
		return str_replace("-", "_", strtolower($page));
	}

	/**
	 * Dispatches the page, the action, and parameters to the
	 * right AController.
	 *
	 * @return string the template to print
	 */
	public final function dispatch() {
		$this->action = self::getAction();
		$this->values = self::getParameters();

		$status = AController::CONTROLLER_MISSING;
		if ($this->controller != NULL) {
			$status = $this->controller->executeAction($this->action, $this->values);
		}
		return $this->handleResponse($status);
	}

	/**
	 * Retrieves the action from the $_GET.<br>
	 * Also replaces "-" into "_".
	 *
	 * @return string the asked action
	 */
	public static final function getAction() {
		if (array_key_exists("action", $_GET) && !empty($_GET['action'])) {
			$action = $_GET['action'];
		} else {
			$action = AController::DEFAULT_ACTION;
		}
		return str_replace("-", "_", strtolower($action));
	}

	/**
	 * Merge GET and POST values into one single array.<br>
	 * Also removes value from page and action used by ADispatcher and AController
	 *
	 * @return array every values
	 */
	public static final function getParameters() {
		$values = array_merge_recursive(array(), $_GET, $_POST);
		if (array_key_exists("page", $values) && !empty($values['page'])) {
			unset($values['page']);
		}
		if (array_key_exists("action", $values) && !empty($values['action'])) {
			unset($values['action']);
		}

		if (array_key_exists("params", $_GET) && !empty($_GET['params'])) {
			$params = array();
			foreach (explode("/", $_GET['params']) as $param) {
				if (empty($param)) {
					continue;
				}
				$params[] = $param;
			}
			$values['params'] = $params;
		}
		return $values;
	}

	/**
	 * Handles the response accordingly.
	 *
	 * @param string $controllerStatus the controller status
	 * @return mixed the value to be printed
	 */
	protected abstract function handleResponse($controllerStatus);

}
