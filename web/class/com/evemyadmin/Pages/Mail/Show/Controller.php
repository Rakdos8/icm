<?php

namespace com\evemyadmin\pages\mail\show;

use com\evemyadmin\controller\AController;
use com\evemyadmin\pages\mail\show\views\Success;
use com\evemyadmin\view\errors\NoActiveCharacterError;
use com\evemyadmin\view\errors\NotConnectedForumError;
use EVEOnline\ESI\Mail\MailLabel;
use EVEOnline\ESI\Mail\MailList;
use net\bourelly\core\view\View;

/**
 * Handles the show action in Mail page
 *
 * @package com.evemyadmin.pages\Mail\Show
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new NotConnectedForumError();
		} else if (is_null($this->session->getActiveCharacter())) {
			return new NoActiveCharacterError();
		}

		// Retrieves characters from the player
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();
		return new Success(
			MailLabel::invoke($oauthUser),
			MailList::invoke($oauthUser),
			!empty($params) && intval($params[0]) > 0 ? intval($params[0]) : 1
		);
	}

}
