<?php

namespace net\bourelly\core\utils\builder;

use com\evemyadmin\controller\AController;
use net\bourelly\core\dispatcher\ADispatcher;

/**
 * Class UrlBuilder.
 *
 * @package net\bourelly\core\utils\builder
 */
class UrlBuilder implements Builder {

	/**
	 * Creates a relative URL from AController object.
	 *
	 * @param AController $controller any AController
	 * @return string the URL
	 */
	public static function createRelativeUrlFromController(AController $controller): string {
		return self::createRelativeUrlFromControllerClass(get_class($controller));
	}

	/**
	 * Creates a relative URL from AController class name.
	 *
	 * @param string $controllerClass the AController class name (with namespace)
	 * @return string the URL
	 */
	public static function createRelativeUrlFromControllerClass(string $controllerClass): string {
		return self::createUrlFromControllerClass($controllerClass, "");
	}

	/**
	 * Creates a full URL from AController object.
	 *
	 * @param AController $controller any AController
	 * @return string the URL
	 */
	public static function createFullUrlFromController(AController $controller): string {
		return self::createFullUrlFromControllerClass(get_class($controller));
	}

	/**
	 * Creates a full URL from AController class name.
	 *
	 * @param string $controllerClass the AController class name (with namespace)
	 * @return string the URL
	 */
	public static function createFullUrlFromControllerClass(string $controllerClass): string {
		return self::createUrlFromControllerClass($controllerClass, FULL_DOMAIN);
	}

	/**
	 * Creates the URL from given parameters.
	 *
	 * @param string $controllerClass the full AController class name with namespace
	 * @param string $domain the domain
	 * @return string the URL
	 */
	private static function createUrlFromControllerClass(string $controllerClass, string $domain): string {
		return $domain . "/" . self::getPageAndActionFromControllerClass($controllerClass);
	}

	/**
	 * Splits the AController class name (with namespace) into Page and Action.
	 *
	 * @param string $controllerClass the full AController class name (with namespace)
	 * @return string the link to page and action
	 */
	private static function getPageAndActionFromControllerClass(string $controllerClass): string {
		// $controllerClass = \net\bourelly\app_name\index\show\Controller
		$controllerPath = str_replace("\\", DIRECTORY_SEPARATOR, $controllerClass);
		// $controllerPath = /net/bourelly/app_name/index/show/Controller
		// Remove useless namespace part
		$controllerPath = str_replace(PATH_APPLICATION_CLASS, "", $controllerPath);
		// $controllerPath = /index/show/Controller
		if (startsWith($controllerPath, "/")) {
			$controllerPath = substr($controllerPath, 1, strlen($controllerPath));
			// $controllerPath = index/show/Controller
		}
		// $controllerPath = index/show/Controller
		$parts = explode("/", $controllerPath);
		$page = $parts[0];
		$action = $parts[1];
		if (strcmp($action, AController::DEFAULT_ACTION) == 0) {
			if (strcmp($page, ADispatcher::DEFAULT_CONTROLLER) == 0) {
				return "";
			}
			return $page;
		}
		return $page . "/" . $action;
	}

}
