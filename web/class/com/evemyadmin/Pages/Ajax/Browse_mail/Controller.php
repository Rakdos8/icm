<?php

namespace com\evemyadmin\pages\ajax\browse_mail;

use com\evemyadmin\controller\AController;
use com\evemyadmin\view\errors\NoActiveCharacterError;
use com\evemyadmin\view\errors\NotConnectedForumError;
use EVEOnline\ESI\EsiFactory;
use net\bourelly\core\view\defaults\debug\DebugView;
use net\bourelly\core\view\defaults\errors\MissingInformation;
use net\bourelly\core\view\View;

/**
 * Handles browsing previous character's mails
 *
 * @package com.evemyadmin.pages\Ajax\Browse_mail
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new NotConnectedForumError();
		} else if (is_null($this->session->getActiveCharacter())) {
			return new NoActiveCharacterError();
		} else if (empty($params) || !is_numeric($params[0])) {
			return new MissingInformation();
		}

		// Retrieves characters from the player
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();
		//TODO: Handles properly the API lost
		$res = EsiFactory::invoke(
			$oauthUser,
			"get",
			"/characters/{character_id}/mail/{mail_id}/",
			array(
				"character_id" => $oauthUser->id_entity,
				"mail_id" => intval($params[0])
			)
		);

		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		return new DebugView($json);
	}

}
