<?php

namespace com\evemyadmin\view\errors;

use net\bourelly\core\view\defaults\ErrorView;

/**
 * Global Error view if the user is not connected to the forum.
 *
 * @package com\evemyadmin\view\errors
 */
class NotConnectedForumError extends ErrorView {

	/**
	 * NotConnectedForumError constructor.
	 */
	public function __construct() {
		parent::__construct(self::getErrorMessage());
	}

	private static function getErrorMessage(): string {
		return '<a href="' . OAUTH_LOGIN_URL . '">'
				. '<img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="EVE SSO login">'
				. "</a><br>"
				. "<br>"
				. 'Vous n\'êtes pas connecté sur le <a href="' . PHPBB_URL . '" target="_blank">forum</a>.';
	}

}
