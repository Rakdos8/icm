<?php

namespace Dispatcher;

use Controller\AController;
use Utils\Handler\PhpBB;

use phpbb\request\request_interface;

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

		$page = self::getPage(PhpBB::getInstance()->getRequest());
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
	 * @param \phpbb\request\request $request the phpbb request
	 * @return string the asked page
	 */
	private static final function getPage($request) {
		$page = $request->variable("page", ADispatcher::DEFAULT_CONTROLLER);
		return str_replace("-", "_", strtolower($page));
	}

	/**
	 * Dispatches the page, the action, and parameters to the
	 * right AController.
	 *
	 * @return string the template to print
	 */
	public final function dispatch() {
		$request = PhpBB::getInstance()->getRequest();
		$this->action = self::getAction($request);
		$this->values = self::getParameters($request);


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
	 * @param \phpbb\request\request $request the phpbb request
	 * @return string the asked action
	 */
	public static final function getAction($request) {
		$action = $request->variable("action", AController::DEFAULT_ACTION);
		return str_replace("-", "_", strtolower($action));
	}

	/**
	 * Merge GET and POST values into one single array.<br>
	 * Also removes value from page and action used by ADispatcher and AController
	 *
	 * @param \phpbb\request\request $request the phpbb request
	 * @return array every values
	 */
	public static final function getParameters($request) {
		$values = array_merge_recursive(
			array(),
			$request->get_super_global(request_interface::GET),
			$request->get_super_global(request_interface::POST)
		);
		if (array_key_exists("page", $values) && !empty($values['page'])) {
			unset($values['page']);
		}
		if (array_key_exists("action", $values) && !empty($values['action'])) {
			unset($values['action']);
		}

		$getParams = $request->variable("params", AController::DEFAULT_ACTION);
		if (is_array($getParams)) {
			$params = array();
			foreach (explode("/", $getParams) as $param) {
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
