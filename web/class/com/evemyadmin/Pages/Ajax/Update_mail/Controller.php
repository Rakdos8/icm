<?php

namespace com\evemyadmin\pages\ajax\update_mail;

use com\evemyadmin\controller\AController;
use com\evemyadmin\view\errors\NoActiveCharacterError;
use com\evemyadmin\view\errors\NotConnectedForumError;
use EVEOnline\ESI\EsiFactory;
use net\bourelly\core\view\defaults\debug\DebugView;
use net\bourelly\core\view\defaults\errors\MissingInformation;
use net\bourelly\core\view\View;

/**
 * Handles updating character's mail
 *
 * @package com.evemyadmin.pages\Ajax\Update_mail
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new NotConnectedForumError();
		} else if (is_null($this->session->getActiveCharacter())) {
			return new NoActiveCharacterError();
		} else if (empty($params) ||
			!array_key_exists("read", $params) ||
			!array_key_exists("mails", $params)
		) {
			return new MissingInformation();
		}

		// Retrieves characters from the player
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();
		foreach (explode(",", $params['mails']) as $mail) {
			EsiFactory::invoke(
				$oauthUser,
				"put",
				"/characters/{character_id}/mail/{mail_id}/",
				array(
					"character_id" => $oauthUser->id_entity,
					"mail_id" => intval($mail)
				),
				array(),
				// Forced to re-set the body each times
				array(
					//"labels" => $params['labels'],
					"read" => $params['read']
				)
			);
		}

		return new DebugView("done");
	}

}
