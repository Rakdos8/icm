<?php

namespace com\evemyadmin\controller;

use com\evemyadmin\model\bean\OAuth2Users;
use com\evemyadmin\model\bean\session\EVECharacter;
use com\evemyadmin\model\bean\UserSession;
use com\evemyadmin\utils\handler\PhpBB;
use net\bourelly\core\controller\common\errors\HttpErrorsController;
use net\bourelly\core\utils\Utils;
use net\bourelly\core\view\View;

/**
 * Handles EVEMyAdmin controllers from this abstract class.
 *
 * @package com.evemyadmin.controller
*/
abstract class AController {

	// Default action on every controller
	const DEFAULT_ACTION = "show";

	/**
	 * @var UserSession $session the current User Session
	 */
	protected $session;

	/**
	 * @var OAuth2Users[] $charactersOAuth linked characters
	 */
	protected $charactersOAuth = array();

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
	 * @param string $action action (show by default)
	 * @return AController the right AController or NULL if any found
	 */
	public static final function getInstance(
			string $page,
			string $action
	): AController {
		// Retrieves the AController if already created
		if (!is_null(self::$INSTANCE)) {
			return self::$INSTANCE;
		}

		// Retrieves the controller full class name which match the page and action
		$controllerPath = self::getControllerPathFromPageAndAction($page, $action);
		$className = str_replace(DIRECTORY_SEPARATOR, "\\", $controllerPath);

		// If the controller is found, include it
		if (is_file(PATH_CLASS . DIRECTORY_SEPARATOR . $controllerPath . ".php")) {
			// Update the class name with the namespace and remove the ".php" extension
			self::$INSTANCE = new $className();
		} else {
			// Avoid loop redirect if it's already the HttpErrorsController
			if (strcmp(HttpErrorsController::class, $controllerPath) == 0) {
				die("Redirect loop !");
			}
			// Log that the template was not found
			Utils::callStack();
			Utils::log("Controller " . PATH_CLASS . "/" . $controllerPath . ".php does not exist !", time());
			Utils::redirect("/errors/404");
		}

		self::$INSTANCE->session = UserSession::getSession();
		self::$INSTANCE->getActiveCharacterFromUser();
		return self::$INSTANCE;
	}

	private static function getControllerPathFromPageAndAction(
			string $page,
			string $action
	) {
		if (strcmp("errors", $page) == 0) {
			return str_replace("\\", DIRECTORY_SEPARATOR, HttpErrorsController::class);
		}

		// Retrieves the controller which match the page and action
		$appPath = endsWith(PATH_APPLICATION_CLASS, "/")
				? substr(PATH_APPLICATION_CLASS, 0, Utils::lastIndexOf(PATH_APPLICATION_CLASS, "/"))
				: PATH_APPLICATION_CLASS;
		return $appPath
				. DIRECTORY_SEPARATOR
				. $page
				. DIRECTORY_SEPARATOR
				. $action
				. DIRECTORY_SEPARATOR
				. "Controller";
	}

	/**
	 * Default action for any AController
	 *
	 * @param array $params parameters in an array
	 * @return View the view to print
	 */
	public abstract function execute(array $params = array()): View;

	/**
	 * @return PhpBB the PhpBB Handler
	 */
	protected function getPhpbbHandler() {
		return PhpBB::getInstance();
	}

	/**
	 * Retrieves current linked characters + prepares the UserSession with it.
	 */
	private function getActiveCharacterFromUser() {
		$currentUserId = $this->getPhpbbHandler()->getUser()->data['user_id'];
		if (!$this->getPhpbbHandler()->isAnonymous()) {
			$this->charactersOAuth = OAuth2Users::getCharacterFromUserId($currentUserId);
		}

		// Security check to not allow user to see other player's character
		if (!$this->getPhpbbHandler()->isDirector() &&
				!is_null($this->session->getActiveCharacter()) &&
				$this->session->getActiveCharacter()->getOauthUser()->id_forum_user != $currentUserId
		) {
			$this->session->setActiveCharacter(NULL);
		}

		foreach ($this->charactersOAuth as $character) {
			$eveCharacter = new EVECharacter($character);
			if ($character->is_main_character && is_null($this->session->getActiveCharacter())) {
				$this->session->setActiveCharacter($eveCharacter);
			}
			$this->session->addEVECharacter($eveCharacter);
		}
	}

	/**
	 * Prints the current instance of AController
	 *
	 * @return string the current AController class name
	 */
	public final function __toString(): string {
		return get_class(self::$INSTANCE);
	}

}
