<?php

namespace net\bourelly\core\utils;

/**
 * AutoLoader.<br>
 * Automatically includes class when being created.
 *
 * @package net\bourelly\core\utils
 * @see spl_autoload_register()
 */
final class AutoLoader {

	/**
	 * Register the custom spl loader.
	 */
	public static function register(): void {
		spl_autoload_register(__CLASS__ . "::loader");
	}

	/**
	 * Loads the class according to its full name
	 *
	 * @param string $className the full class name
	 * @return bool true if the class exists and is included, false otherwise
	 */
	public static function loader(string $className): bool {
		$filePath = PATH_CLASS
				. DIRECTORY_SEPARATOR
				. str_replace("\\", DIRECTORY_SEPARATOR, $className)
				. ".php";
		if (file_exists($filePath)) {
			require_once $filePath;
			return class_exists($className);
		}
		return false;
	}

}
