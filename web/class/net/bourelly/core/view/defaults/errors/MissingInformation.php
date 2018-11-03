<?php

namespace net\bourelly\core\view\defaults\errors;

use net\bourelly\core\view\defaults\ErrorView;

/**
 * Global Error view if the user didn't send every mandatory fields
 *
 * @package net\bourelly\core\view\defaults\errors
 */
class MissingInformation extends ErrorView {

	/**
	 * MissingInformation constructor.
	 */
	public function __construct() {
		parent::__construct("Il manque des informations pour pouvoir traiter cette action.");
	}

}
