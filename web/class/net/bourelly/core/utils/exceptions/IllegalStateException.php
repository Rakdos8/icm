<?php

namespace net\bourelly\core\utils\exceptions;

/**
 * Called when the state/status is not permitted.
 *
 * @package net\bourelly\core\utils\exceptions
 */
class IllegalStateException extends \RuntimeException {

	/**
	 * IllegalStateException constructor.
	 *
	 * @param string $message the message of the error
	 */
	public function __construct(string $message) {
		parent::__construct($message);
	}

}
