<?php

namespace com\evemyadmin\pages\login\show;

use com\evemyadmin\controller\AController;
use net\bourelly\core\view\View;
use Pages\Login\Show\Views\Success;

/**
 * Handles the show action in Index page
 *
 * @package com.evemyadmin.pages\Index\Show
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		return new Success();
	}

}
