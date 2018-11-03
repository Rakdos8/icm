<?php

namespace net\bourelly\core\dispatcher;

use net\bourelly\core\view\View;

/**
 * Dispatcher for the PHP
 *
 * @package net\bourelly\core\dispatcher
 */
final class PHP extends ADispatcher {

	/**
	 * PHP Dispatcher constructor.
	 *
	 * @param string $page the page name
	 */
	protected function __construct(string $page) {
		parent::__construct($page);
	}

	protected final function handleResponse(): View {
		return $this->controller->execute($this->getParameters());
	}

}
