<?php

namespace View\Errors;

use View\ErrorView;

/**
 * Class Error403 for the show in Errors controller
 *
 * @package View\Index\Show
 */
class Error403 extends ErrorView {

	public function showTemplate() {
?>
<p class="erreur">Accès refusé !</p>
<?php
	}

}