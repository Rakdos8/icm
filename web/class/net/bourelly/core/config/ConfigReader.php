<?php

namespace net\bourelly\core\config;

/**
 * Class ConfigReader.<br>
 * Reads the ini config file and set defines accordingly.
 *
 * @package net\bourelly\core\config
 */
abstract class ConfigReader {

	protected $pathIniFile;

	public function __construct(
			string $pathIniFile
	) {
		$this->pathIniFile = $pathIniFile;
	}

	/**
	 * Read the ini file and defines them
	 *
	 * @return bool true if the parsing is all correct, false otherwise
	 */
	public function parseConfig(): bool {
		$iniConfig = $this->parseIniFile();
		if (!is_array($iniConfig)) {
			return false;
		}
		$this->parse($iniConfig);
		return true;
	}

	/**
	 * Parse the ini file.
	 *
	 * @return array|bool The settings are returned as an associative array on success,
	 * and false on failure.
	 * @see parse_ini_file()
	 */
	protected function parseIniFile() {
		if (is_null($this->pathIniFile) || !is_file($this->pathIniFile)) {
			return false;
		}
		return parse_ini_file($this->pathIniFile, false, INI_SCANNER_TYPED);
	}

	/**
	 * Read the ini file and defines them
	 *
	 * @param array $iniConfig the ini array config file
	 * @return bool true if the parsing is all correct, false otherwise
	 */
	protected abstract function parse(array $iniConfig): bool;

}
