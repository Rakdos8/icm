<?php

namespace com\evemyadmin\pages\entity\contract;

use com\evemyadmin\controller\AController;
use com\evemyadmin\view\errors\NoActiveCharacterError;
use com\evemyadmin\view\errors\NotConnectedForumError;
use EVEOnline\ESI\EsiFactory;
use net\bourelly\core\view\defaults\debug\DebugView;
use net\bourelly\core\view\View;

/**
 * Handles the show action in Index page
 *
 * @package com.evemyadmin.pages\Entity\Contract
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new NotConnectedForumError();
		}

		if (is_null($this->session->getActiveCharacter())) {
			return new NoActiveCharacterError();
		}

		// Retrieves characters from the player
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();
		$res = EsiFactory::invoke(
			$oauthUser,
			"get",
			"/characters/{character_id}/contracts/",
			array("character_id" => $oauthUser->id_entity)
		);

		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		return new DebugView($json);
	}

}
