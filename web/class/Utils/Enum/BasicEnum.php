<?php

namespace Utils\Enum;

/**
 * Abstract class for any enum.
 *
 * @package Utils\Enum
 * @see https://stackoverflow.com/questions/254514/php-and-enumerations?answertab=votes#tab-top
 */
abstract class BasicEnum {

	private static $constCacheArray = array();

	private static function getConstants() {
		if (self::$constCacheArray == NULL) {
			self::$constCacheArray = [];
		}
		$calledClass = get_called_class();
		if (!array_key_exists($calledClass, self::$constCacheArray)) {
			$reflect = new \ReflectionClass($calledClass);
			self::$constCacheArray[$calledClass] = $reflect->getConstants();
		}
		return self::$constCacheArray[$calledClass];
	}

	/**
	 * Is the given name a valid constant ?
	 *
	 * @param string $name the name
	 * @param bool $strict should it be case sensitive ?
	 * @return bool true if the constant exists, false otherwise
	 */
	public static function isValidName($name, $strict = false) {
		$constants = self::getConstants();

		if ($strict) {
			return array_key_exists($name, $constants);
		}

		$keys = array_map("strtolower", array_keys($constants));
		return in_array(strtolower($name), $keys);
	}

	/**
	 * Is the given value a valid one ?
	 *
	 * @param mixed $value the value
	 * @param bool $strict should it be case sensitive ?
	 * @return bool true if the value exists, false otherwise
	 */
	public static function isValidValue($value, $strict = true) {
		$values = array_values(self::getConstants());
		return in_array($value, $values, $strict);
	}

}