<?php

namespace net\bourelly\core\view;

use net\bourelly\core\utils\builder\BreadcrumbBuilder;

/**
 * Returns the default Breadcrumb.
 *
 * @package net\bourelly\core\view
 */
trait DefaultBreadcrumb {

	/**
	 * Retrieves the current breadcrumb.
	 *
	 * @return string the HTML breadcrumb
	 */
	public function getBreadcrumb(): string {
		return BreadcrumbBuilder::createBreadcrumb();
	}

}
