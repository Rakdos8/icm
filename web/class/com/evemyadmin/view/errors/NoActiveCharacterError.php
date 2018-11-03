<?php

namespace com\evemyadmin\view\errors;

use net\bourelly\core\view\defaults\ErrorView;

/**
 * Global Error view if the user didn't choose a character
 *
 * @package com\evemyadmin\view\errors
 */
class NoActiveCharacterError extends ErrorView {

	/**
	 * NoActiveCharacterError constructor.
	 */
	public function __construct() {
		parent::__construct("Vous n'avez pas sélectionné de personnage.");
	}

}
