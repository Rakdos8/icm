<?php

namespace com\evemyadmin\pages\callback\change_character;

use com\evemyadmin\controller\AController;
use com\evemyadmin\model\bean\OAuth2Users;
use com\evemyadmin\model\bean\session\EVECharacter;
use net\bourelly\core\utils\Utils;
use net\bourelly\core\view\View;

/**
 * Handles the change character on the Callback page
 *
 * @package com.evemyadmin.pages\Callback\Change_character
 */
final class Controller extends AController {

	/**
	 * Called when the user wants to change his current character
	 *
	 * @param array $params
	 * @return View the view, will always be null
	 */
	public function execute(array $params = array()): View {
		// The code was not given, might be a manual call to this page.
		if (empty($params)) {
			Utils::redirect($this->session->getActiveUri());
		}

		$characterId = reset($params);
		if (!is_numeric($characterId)) {
			Utils::redirect($this->session->getActiveUri());
		}

		$oauthUser = OAuth2Users::getCharacterFromCharacterId($characterId);
		if (is_null($oauthUser)) {
			Utils::redirect($this->session->getActiveUri());
		}

		if ($this->getPhpbbHandler()->isDirector() ||
			$oauthUser->id_forum_user == $this->getPhpbbHandler()->getUser()->data['user_id']
		) {
			$this->session->setActiveCharacter(new EVECharacter($oauthUser));
			Utils::redirect($this->session->getActiveUri());
		}
		Utils::redirect($this->session->getActiveUri());

		return NULL;
	}

}
