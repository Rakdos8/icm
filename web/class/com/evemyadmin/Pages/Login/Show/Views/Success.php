<?php

namespace Pages\Login\Show\Views;

use net\bourelly\core\view\DefaultBreadcrumb;
use net\bourelly\core\view\View;

/**
 * Class Success for the show in Index controller
 *
 * @package com.evemyadmin.pages\Index\Show\Views
 */
class Success implements View {

	use DefaultBreadcrumb;

	public function getPageTitle(): string {
		return "Login";
	}

	public function showHtmlTemplate() {
?>
		Pour profiter pleinement d'<abbr title="EVEMyAdmin">EMA</abbr>, merci de synchroniser votre personnage.<br>
<a href="<?= OAUTH_LOGIN_URL ?>">
	<img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="EVE SSO login">
</a>
<?php
	}

	public function getJsonTemplate() {
		// TODO: Implement getJsonTemplate() method.
	}

}
