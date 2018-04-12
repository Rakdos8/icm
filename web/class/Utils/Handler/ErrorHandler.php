<?php

namespace Utils\Handler;

use phpbb\request\request_interface;
use Utils\Utils;

/**
 * Error Handler.
 *
 * @see set_error_handler()
 */
final class ErrorHandler implements Handler {

	/**
	 * Saved errors of the session to avoid error spam.
	 *
	 * @var string[]
	 * @static
	 */
	private static $ERRORS = array();

	/**
	 * Register the custom error handler
	 */
	public static function register() {
		set_error_handler(__CLASS__ . "::log");
		set_exception_handler(__CLASS__ . "::logException");
	}

	/**
	 * Logs the message/error and send a debug mail to MAIL_DEVELOPER if not a E_NOTICE.
	 *
	 * @param integer $number error number
	 * @param string $errMessage error message
	 * @param string $file file path
	 * @param integer $line line in the file
	 * @param mixed $vars vars
	 */
	public static function log($number, $errMessage, $file, $line, $vars) {
		// Silent operator (@) activated ?
		if (error_reporting() === 0) {
			return;
		}
		$request = PhpBB::getInstance()->getRequest();
		// Retrieves the requested URL from $_SERVER through \phpbb\request\request
		if (strcmp(self::getServerVariable($request, "HTTP_REFERER", "%%WRONG_URL%%"), "%%WRONG_URL%%") == 0) {
			$url = self::getServerVariable($request, "REQUEST_SCHEME", "http") . "://" .
				self::getServerVariable($request, "HTTP_HOST", "evemyadmin.com") .
				self::getServerVariable(
					$request,
					"REQUEST_URI",
					self::getServerVariable(
						$request,
						"REDIRECT_URL",
						self::getServerVariable(
							$request,
							"REDIRECT_QUERY_STRING"
						)
					)
				);
		} else {
			$url = self::getServerVariable($request, "HTTP_REFERER", "Unknown URL");
		}

		// Only logs if the error is new
		if (in_array($url . $errMessage, self::$ERRORS)) {
			return;
		}
		self::$ERRORS[] = $url . $errMessage;

		$prefix = "[" . Utils::dateJJ_MM_AAAA(true, time()) . "] ";
		$message = $prefix . "URL: " . $url . "\r\n";
		$message .= $prefix . "Erreur: " . self::getPhpErrorFromNumber($number) . " (" . $number . ")" . "\r\n";
		$message .= $prefix . "Message: " . $errMessage . "\r\n";
		$message .= $prefix . "\r\n";
		$message .= $prefix . "Stack trace:" . "\r\n";
		$message .= Utils::callStack(false);

		// Save the error in the file
		Utils::log($message . str_repeat("=", 60));

		$message = str_replace("\r\n", "<br>", $message);
		$message = str_replace(
			array($prefix . "Stack trace:<br>"),
			array($prefix . "Stack trace:<br><pre>"),
			$message
		);
		$message .= "</pre>";

		Utils::sendMail(
			"EMA - Erreur le " . Utils::dateJJ_MM_AAAA(true, time()),
			"<html><body><h1>Une erreur PHP est survenue sur le site de EVEMyAdmin !</h1>" . $message .
			"<br><br>Cette erreur a aussi été loggé dans le fichier " . PATH_LOG_PHP_ERROR . ".</body></html>",
			MAIL_DEVELOPER
		);
	}

	/**
	 * Logs the Exception and send a debug mail to MAIL_DEVELOPER if not an INFO.
	 *
	 * @param \Exception $exception the exception thrown
	 */
	public static function logException($exception) {
		//TODO: the log function must throw an Exception ad handling done here
		//(with recursive due to previous Exception if any)
		self::log(
			$exception->getCode(),
			$exception->getMessage(),
			$exception->getFile(),
			$exception->getLine(),
			$exception->getTraceAsString()
		);
	}

	/**
	 * Retrieves the type of error according to its level.
	 *
	 * @param integer $errorNumber error value
	 * @return string error type
	 */
	private static function getPhpErrorFromNumber($errorNumber) {
		switch ($errorNumber) {
			case E_ERROR:
				return "E_ERROR";
			case E_WARNING:
				return "E_WARNING";
			case E_PARSE:
				return "E_PARSE";
			case E_NOTICE:
				return "E_NOTICE";
			case E_CORE_ERROR:
				return "E_CORE_ERROR";
			case E_CORE_WARNING:
				return "E_CORE_WARNING";
			case E_COMPILE_ERROR:
				return "E_COMPILE_ERROR";
			case E_COMPILE_WARNING:
				return "E_COMPILE_WARNING";
			case E_USER_ERROR:
				return "E_USER_ERROR";
			case E_USER_WARNING:
				return "E_USER_WARNING";
			case E_USER_NOTICE:
				return "E_USER_NOTICE";
			case E_STRICT:
				return "E_STRICT";
			case E_RECOVERABLE_ERROR:
				return "E_RECOVERABLE_ERROR";
			case E_DEPRECATED:
				return "E_DEPRECATED";
			case E_USER_DEPRECATED:
				return "E_USER_DEPRECATED";
			case E_ALL:
				return "E_ALL";
		}
		return "E_UNKNOWN";
	}

	/**
	 * Retrieves the variable value from the request.
	 *
	 * @param \phpbb\request\request $request the request to retrieve data
	 * @param string $varName the variable names
	 * @param string $defValue the default value
	 * @return string the value of the default value
	 */
	private static function getServerVariable($request, $varName, $defValue = "UNKNOWN") {
		return $request->variable($varName, $defValue, true, request_interface::SERVER);
	}

}
