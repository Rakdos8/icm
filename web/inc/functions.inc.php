<?php

/**
 * Allows to print any var properly.<br>
 * Can also stop PHP execution script if required.
 *
 * @param mixed $var variable to print
 * @param bool $die stop php script (false by default)
 */
function debug($var, $die = false) {
	echo "<pre>" . print_r($var, true) . "</pre>";
	if ($die) {
		die;
	}
}

/**
 * Allows to print any var properly.<br>
 * Can also stop PHP execution script if required.
 *
 * @param string $key the key in the array
 * @param array $array the array to search in
 * @param mixed $defaultValue default value if not exist
 * @return mixed the expected value (can also be the default one)
 */
function getValueInArrayOrDefault(string $key, array $array, $defaultValue) {
	$value = $defaultValue;
	if (array_key_exists($key, $array)) {
		$value = $array[$key];
	}
	return $value;
}

/**
 * Does the given haystack start with the given needle ?
 *
 * @param string $haystack the full string
 * @param string $needle the needle
 * @return bool true if it starts with, false otherwise
 */
function startsWith(string $haystack, string $needle): bool {
	$length = strlen($needle);
	return strcmp(substr($haystack, 0, $length), $needle) == 0;
}

/**
 * Does the given haystack end with the given needle ?
 *
 * @param string $haystack the full string
 * @param string $needle the needle
 * @return bool true if it ends with, false otherwise
 */
function endsWith(string $haystack, string $needle): bool {
	$length = strlen($needle);
	if ($length == 0) {
		return true;
	}

	return strcmp(substr($haystack, -$length), $needle) == 0;
}

/**
 * Creates the file if missing.<br>
 * Will die if can't create it.
 *
 * @param string $filePath the path of the file to create if messing
 */
function createMissingFile($filePath = "") {
	if (is_null($filePath) || empty($filePath)) {
		return;
	}
	$openedFile = fopen($filePath, "a");
	if (!is_file($filePath)) {
		die("Impossible to create file: '" . $filePath . "' !");
	} else {
		fclose($openedFile);
	}
}
