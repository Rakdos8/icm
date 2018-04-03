<?php

namespace Controller;

use Utils\Utils;

// Inclusion du modèle principal gérant l'abstraction de la BDD
require_once PATH_MODEL . "/Model.base.php";

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
			require_once $controllerFile;
			// Update the class name with the namespace of the current one: Controller
			$className = __NAMESPACE__ . "\\" . $page;
			self::$INSTANCE = new $className();
		} else {
			// Log that the template was not found
			Utils::callStack();
			Utils::log("Controller " . $controllerFile . " does not exist !", time());
			Utils::redirect("/404");
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
		if (array_key_exists($name, $this->values) && !empty($this->values[$name])) {
			return $this->values[$name];
		}
		return NULL;
	}

// ----------------------------------------------------------------------------------
// ---------------------- Fonctions utilitaires sur les array -----------------------
// ----------------------------------------------------------------------------------
	/**
	 * Fonction de surchage pour htmlentities pour le traitement des arrays
	 *
	 * @param mixed $chaine Chaine ou array à parcourir pour encoder
	 * @return string Chaîne encodée
	 */
	public static final function htmlentities_array_map($chaine) {
		// Appel récursif pour parcourir le tableau si nécessaire
		if (is_array($chaine)) {
			foreach ($chaine as $sousChaine) {
				self::htmlentities_array_map($sousChaine);
			}
		} else {
			return trim(
				htmlentities($chaine, ENT_QUOTES, "UTF-8", false),
				" \t\n\r\0\x0B"
			);
		}
	}

	/**
	 * Fonction de surchage pour html_entity_decode pour le traitement des arrays
	 *
	 * @param mixed $chaine Chaine ou array à parcourir pour décoder
	 * @return string Chaîne décodée
	 */
	public static final function html_entity_decode_array_map($chaine) {
		// Appel récursif pour parcourir le tableau si nécessaire
		if (is_array($chaine)) {
			foreach ($chaine as $sousChaine) {
				self::html_entity_decode_array_map($sousChaine);
			}
		} else {
			return html_entity_decode($chaine, ENT_QUOTES, "UTF-8");
		}
	}

// ----------------------------------------------------------------------------------
// -------------------- Fonctions utilitaires sur les SESSION -----------------------
// ----------------------------------------------------------------------------------
	/**
	 * Crée ou modifie une session avec son nom et sa valeur
	 *
	 * @param string $name Nom de la session à créer ou modifié
	 * @param mixed $value Valeur de la session à créer ou modifié
	 * @return boolean Retourne true si la session a bien été crée ou modifié. False sinon
	 */
	public static final function setSession($name, $value) {
		$_SESSION[$name] = $value;
	}

	/**
	 * Récupère la valeur du champ de la session en cours
	 *
	 * @param string $name Nom du champ de la session à récupérer
	 * @return mixed Récupère la valeur du champ donné. NULL si non trouvé.
	 */
	public static final function getSession($name) {
		return isset($_SESSION[$name]) ? $_SESSION[$name] : NULL;
	}

	/**
	 * Supprime l'information de la session ou toutes les informations de la session courante
	 *
	 * @param mixed $name Nom du l'information à supprimer. Si NULL est donné, il supprimera TOUTES les informations.
	 * @return integer Retourne le nombre d'information qui a été supprimé: doit être supérieur ou égal à 0 sinon une erreur a eu lieu.
	 */
	public static final function deleteSession($name) {
		$nbSession = 0;

		if ($name === NULL) {
			// Récupération des cookies existants
			$nomsInformations = array_keys($_SESSION);
			// On les supprime tous les uns après les autres
			foreach ($nomsInformations as $name) {
				$nbSession++;
				self::setSession($name, NULL);
			}
		} else {
			$nbSession++;
			self::setSession($name, NULL);
		}

		return $nbSession;
	}

// ----------------------------------------------------------------------------------
// --------------------- Fonctions utilitaires sur les COOKIE -----------------------
// ----------------------------------------------------------------------------------
	/**
	 * Vérifie si les cookies (disclamer) ont été accepté par l'internaute
	 *
	 * @return boolean Renvoie true si les cookies sont acceptés, false sinon.
	 */
	public static final function is_cookieAcceptes() {
		if (strstr(self::getCookie(COOKIE_ACCEPTE), "true") !== false) {
			return true;
		}
		return false;
	}

	/**
	 * Crée ou modifie un cookie avec son nom et sa valeur
	 *
	 * @param string $name Nom du cookie à créer ou modifié
	 * @param mixed $valeurvalue Valeur du cookie à créer ou modifié
	 * @param integer $expire Permet de redéfinir l'expiration. Par défaut: 1 mois
	 * @return boolean Retourne true si le cookie a bien été crée ou modifié. False sinon
	 */
	public static final function setCookie($name, $valeurvalue, $expire = COOKIE_EXPIRATION) {
		setcookie($name, $valeurvalue, time() + $expire, COOKIE_CHEMIN, COOKIE_DOMAINE, false, true);
	}

	/**
	 * Récupère la valeur du cookie donné
	 *
	 * @param string $name Nom du cookie à récupérer
	 * @return mixed Récupère la valeur du champ donné. NULL si non trouvé.
	 */
	public static final function getCookie($name) {
		if (array_key_exists($name, $_COOKIE)) {
			return $_COOKIE[$name];
		}
		return NULL;
	}

	/**
	 * Supprime le cookie donné ou tous les cookies
	 *
	 * @param mixed $nom Nom du cookie à supprimer. Si NULL est donné, il supprimera TOUS les cookies.
	 * @return integer Retourne le nombre de cookie qui a été supprimé: doit être supérieur ou égal à 0 sinon une erreur a eu lieu.
	 */
	public static final function deleteCookie($nom) {
		$nbCookie = 0;

		if ($nom === NULL) {
			// Récupération des cookies existants
			$nomsCookies = array_keys($_COOKIE);
			// On les supprime tous les uns après les autres
			foreach ($nomsCookies as $nom) {
				// Si le nom du cookie n'est pas celui qui permet de vérifier qu'ils ont été accepté, on supprime
				if ($nom != COOKIE_ACCEPTE) {
					self::setCookie($nom, NULL, -1) ? $nbCookie++ : $nbCookie--;
				}
			}
		} else {
			self::setCookie($nom, NULL, -1) ? $nbCookie++ : $nbCookie--;
		}

		return $nbCookie;
	}

}
