<?php

namespace net\bourelly\core\view;

/**
 * Returns an empty Breadcrumb.
 *
 * @package net\bourelly\core\view
 */
trait EmptyBreadcrumb {

	/**
	 * Retrieves the current breadcrumb (or empty if not required).
	 *
	 * @return string the HTML breadcrumb
	 */
	public function getBreadcrumb(): string {
		$breadcrumb = "<ol class=\"breadcrumb float-right\">";
/*
			<li class="breadcrumb-item"><a href="#">Minton</a></li>
			<li class="breadcrumb-item active">Dashboard</li>
*/
		return $breadcrumb . "</ol>";
	}

}
