<?php

namespace View\Index\Channels;

use View\ErrorView;

/**
 * Class Error for the show in Index controller
 *
 * @package View\Index\Show
 */
class Error extends ErrorView {

	private $message;

	/**
	 * Error constructor.
	 * @param $message
	 */
	public function __construct($message) {
		$this->message = $message;
	}

	public function showTemplate() {
		echo $this->message;
	}
}