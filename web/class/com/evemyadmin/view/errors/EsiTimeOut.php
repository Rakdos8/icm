<?php

namespace com\evemyadmin\view\errors;

use net\bourelly\core\view\DefaultBreadcrumb;
use net\bourelly\core\view\defaults\ErrorView;

/**
 * Class Success for the show in Index controller
 *
 * @package com\evemyadmin\view\errors
 */
class EsiTimeOut extends ErrorView {

	use DefaultBreadcrumb;

	public function __construct() {
		parent::__construct("L'ESI a quelques difficultés pour répondre dans les temps. Désolé pour la gêne occasionnée.");
	}

	public function getPageTitle(): string {
		return "Erreur ESI";
	}

}
