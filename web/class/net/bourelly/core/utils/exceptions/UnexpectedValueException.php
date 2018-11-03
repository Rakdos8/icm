<?php

namespace net\bourelly\core\utils\exceptions;

/**
 * Called when the request value from a BasicEnum is not in the constant list.
 *
 * @package net\bourelly\core\utils\exceptions
 */
class UnexpectedValueException extends \RuntimeException {

	/**
	 * UnexpectedValueException constructor.
	 *
	 * @param string $message the message of the error
	 */
	public function __construct(string $message) {
		parent::__construct($message);
	}

}
