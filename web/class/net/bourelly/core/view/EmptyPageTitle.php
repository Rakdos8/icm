<?php

namespace net\bourelly\core\view;

/**
 * Returns an empty page's title.
 *
 * @package net\bourelly\core\view
 */
trait EmptyPageTitle {

	/**
	 * Retrieves the page title.
	 *
	 * @return string the current page to be printed
	 */
	public function getPageTitle(): string {
		return "";
	}

}
