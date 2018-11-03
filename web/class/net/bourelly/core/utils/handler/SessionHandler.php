<?php

namespace net\bourelly\core\utils\handler;

/**
 * The Session ($_SESSION) handler.
 *
 * @package net\bourelly\core\utils\handler
 */
class SessionHandler implements Handler {

	/**
	 * Creates or modifies value of a session name
	 *
	 * @param string $name name of the session
	 * @param mixed $value value to set in the session
	 */
	public static function setSession(string $name, $value): void {
		$_SESSION[$name] = $value;
	}

	/**
	 * Retrieves the value of the given session name.
	 *
	 * @param string $name name of the session
	 * @param mixed $defaultValue the default value if does not exist
	 * @return mixed the value if found, null otherwise
	 */
	public static function getFromSession(string $name, string $defaultValue = NULL) {
		return getValueInArrayOrDefault(
				$name,
				$_SESSION,
				$defaultValue
		);
	}

	/**
	 * Deletes informations from the session.
	 *
	 * @param mixed $name name of the session, if NULL given, it will delete every data in it
	 * @return integer the number of data deleted
	 */
	public static function deleteSession(string $name): int {
		$nbSession = 0;

		if (is_null($name)) {
			$keys = array_keys($_SESSION);
			// Delete them one by one
			foreach ($keys as $name) {
				$nbSession++;
				self::setSession($name, NULL);
			}
		} else {
			$nbSession++;
			self::setSession($name, NULL);
		}

		return $nbSession;
	}

}
