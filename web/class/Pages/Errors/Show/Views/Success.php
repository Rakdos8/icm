<?php

namespace Pages\Errors\Show\Views;

use View\DefaultBreadcrumb;
use View\ErrorView;

/**
 * Class Success for the show in Index controller
 *
 * @package Pages\Index\Show\Views
 */
class Success extends ErrorView {

	use DefaultBreadcrumb;

	public function getPageTitle() {
		return "Errors";
	}

	public function showTemplate() {
?>
L'ESI a quelques difficultés pour répondre dans les temps. Désolé pour la gêne occasionnée.
<?php
	}
}
