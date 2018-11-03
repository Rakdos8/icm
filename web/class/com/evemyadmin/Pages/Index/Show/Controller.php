<?php

namespace com\evemyadmin\pages\index\show;

use com\evemyadmin\controller\AController;
use com\evemyadmin\pages\index\show\views\Success;
use com\evemyadmin\view\errors\NotConnectedForumError;
use EVEOnline\ESI\Character\CharacterDetails;
use EVEOnline\ESI\EsiFactory;
use net\bourelly\core\view\View;

/**
 * Handles the show action in Index page
 *
 * @package com.evemyadmin.pages.index.show
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new NotConnectedForumError();
		}

		// Retrieves characters from the player
		$characters = array();
		foreach ($this->charactersOAuth as $character) {
			$res = EsiFactory::invoke(
				$character,
				"get",
				"/characters/{character_id}/",
				array("character_id" => $character->id_entity)
			);

			// Retrieve the raw JSON
			$json = json_decode($res->raw, true);
			$characters[] = CharacterDetails::create(
				$character->id_entity,
				$json
			);
		}
		return new Success($characters);
	}

}
