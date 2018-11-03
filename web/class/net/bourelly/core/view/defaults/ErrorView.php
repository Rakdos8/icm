<?php

namespace net\bourelly\core\view\defaults;

use net\bourelly\core\view\EmptyBreadcrumb;
use net\bourelly\core\view\EmptyPageTitle;
use net\bourelly\core\view\View;

/**
 * Class ErrorView
 *
 * @package net\bourelly\core\view
 */
class ErrorView implements View {

	use EmptyPageTitle;
	use EmptyBreadcrumb;

	const DEFAULT_ERROR = "Erreur inconnue !";

	private $error;

	/**
	 * ErrorView constructor.
	 *
	 * @param string $error the error message
	 */
	public function __construct(string $error = self::DEFAULT_ERROR) {
		$this->error = $error;
	}

	public function showHtmlTemplate() {
?>
<p class="error"><?= $this->error; ?></p>
<?php
	}

	public function getJsonTemplate() {
		return $this->error;
	}

}
