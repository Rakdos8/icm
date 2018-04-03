<?php

namespace Utils;

/**
 * AutoLoader handler.
 *
 * @see spl_autoload_register()
 */
class AutoLoader {

	/**
	 * Register the custom spl loader.
	 */
	public static function register() {
		spl_autoload_register("Utils\\AutoLoader::loader");
	}

	/**
	 * Creates the auto loader class by their name.
	 *
	 * @param string $className the full class name
	 * @return bool true if the class exists and is included, false otherwise
	 */
	public static function loader($className) {
		$filePath = PATH_CLASS . "/" . str_replace("\\", DIRECTORY_SEPARATOR, $className) . ".php";
		if (file_exists($filePath)) {
			include_once $filePath;
			return class_exists($className);
		}
		return false;
	}

}