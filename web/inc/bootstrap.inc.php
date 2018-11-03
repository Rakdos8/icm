<?php

require_once "functions.inc.php";

/** Global configuration */
header("charset=UTF-8");
mb_internal_encoding("UTF-8");
date_default_timezone_set("Europe/Paris");
setlocale(LC_ALL, "fr_FR.UTF-8", "fr_FR.UTF8");

/** Global definition for required and global path */
define("PATH_PROJECT", realpath(__DIR__ . "/../"), false);
define("PATH_ROOT", realpath(PATH_PROJECT . "/../"), false);
define("PATH_CONFIG", PATH_PROJECT . "/conf", false);
define("PATH_INCLUDE", PATH_PROJECT . "/inc", false);
define("PATH_COMPOSER", PATH_PROJECT . "/vendor", false);
define("PATH_CLASS", PATH_PROJECT . "/class", false);
define("PATH_LOG", PATH_ROOT . "/log", false);
define("PATH_LOG_PHP_ERROR", PATH_LOG . "/php_errors.log", false);
define("PATH_LOG_SQL_ERROR", PATH_LOG . "/sql_errors.log", false);

define("PATH_UTILITY", PATH_CLASS . "/Utils", false);
define("PATH_CONTROLLER", PATH_CLASS . "/Controller", false);

/** Defines how to load PHP Class through namespace/package */
require_once PATH_CLASS . "/net/bourelly/core/utils/AutoLoader.php";
\net\bourelly\core\utils\AutoLoader::register();

// Creates PHP log file
createMissingFile(PATH_LOG_PHP_ERROR);
// Creates SQL log file
createMissingFile(PATH_LOG_SQL_ERROR);
// Includes the ErrorHandler
\net\bourelly\core\utils\handler\ErrorHandler::register();

// Saves every error (even NOTICE and WARNING)
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("log_errors_max_len", 1024);
// Sets the file path for PHP NOTICE, WARNING, FATAL, etc.
ini_set("error_log", PATH_LOG_PHP_ERROR);
// Raise PHP memory limit to 64MB
ini_set("memory_limit", "64M");
// Sets the temporary file (for upload) in the right folder
ini_set("upload_tmp_dir", PATH_ROOT . "/tmp");


// Reading main config file
$mainConfig = new \com\evemyadmin\config\MainConfigReader(PATH_CONFIG . "/config.ini");
if (!$mainConfig->parseConfig()) {
	die("Impossible to read config file !");
}
// Security measure
unset($mainConfig);

/** Defines some custom values for PHP */
// Print error if asked for
ini_set("display_errors", DEBUG ? "1" : "0");

// Sets the active domain to the current domain name
ini_set("session.cookie_domain", "." . DOMAIN);
session_set_cookie_params(0, "/", "." . DOMAIN, true, true);

// PhpBB links
define("IN_PHPBB", true);
$phpEx = "php";
$phpbb_root_path = PATH_PHPBB . "/";
require_once PATH_PHPBB . "/common." . $phpEx;
/**
 * @var \phpbb\request\request $request the request
 */
global $request;
$request->enable_super_globals();

// Re-set back the "right" Class Loader to use the current one and not the PhpBB one
require_once PATH_COMPOSER . "/autoload.php";
