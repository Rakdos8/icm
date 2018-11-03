<?php

namespace net\bourelly\core\utils\handler;

/**
 * The Cookie ($_COOKIE) handler
 *
 * @package net\bourelly\core\utils\handler
 */
class CookieHandler implements Handler {

	// Constant of common value in cookie
	const ACTIVE_URI = "active_uri";

	/**
	 * Checks if the cookie are allowed.
	 *
	 * @return boolean true if cookie are accepted, false otherwise
	 */
	public static function isCookieAllowed(): bool {
		if (strstr(self::getFromCookie(DISCLAIMER_NAME, "false"), "true") !== false) {
			return true;
		}
		return false;
	}

	/**
	 * Creates or modifies value of a cookie name
	 *
	 * @param string $name name of the cookie
	 * @param mixed $value value to set in the cookie
	 * @param int $expiration the expiration time of the given cookie
	 */
	public static function setCookie(string $name, $value, int $expiration = COOKIE_DEFAULT_EXPIRATION): void {
		if (!self::isCookieAllowed()) {
			return;
		}
		setcookie(
				$name,
				$value,
				time() + $expiration,
				"/",
				DOMAIN,
				true,
				true
		);
	}

	/**
	 * Retrieves the value of the given cookie name.
	 *
	 * @param string $name name of the cookie
	 * @param mixed $defaultValue the default value if does not exist
	 * @return mixed the value if found, null otherwise
	 */
	public static function getFromCookie(string $name, string $defaultValue = NULL) {
		return getValueInArrayOrDefault(
				$name,
				$_COOKIE,
				$defaultValue
		);
	}

	/**
	 * Deletes informations from the cookie.
	 *
	 * @param mixed $name name of the cookie, if NULL given, it will delete every data in it
	 * @return integer the number of data deleted
	 */
	public static function deleteCookie(string $name): int {
		$nbCookie = 0;

		if (is_null($name)) {
			$keys = array_keys($_COOKIE);
			// Delete them one by one
			foreach ($keys as $name) {
				$nbCookie++;
				self::setCookie($name, NULL, -1);
			}
		} else {
			$nbCookie++;
			self::setCookie($name, NULL, -1);
		}

		return $nbCookie;
	}

}
