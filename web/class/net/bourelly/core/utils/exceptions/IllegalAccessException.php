<?php

namespace net\bourelly\core\utils\exceptions;

/**
 * Called when the access should not be permitted.
 *
 * @package net\bourelly\core\utils\exceptions
 */
class IllegalAccessException extends \RuntimeException {

	/**
	 * IllegalAccessException constructor.
	 *
	 * @param string $message the message of the error
	 */
	public function __construct(string $message) {
		parent::__construct($message);
	}

}
