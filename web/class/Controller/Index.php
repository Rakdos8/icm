<?php

namespace Controller;

use EVEOnline\ESI\Character\CharacterDetails;
use EVEOnline\ESI\EsiFactory;
use Model\table\OAuth2Users;
use View\Index\Show\Error;
use View\Index\Show\Success;

/**
 * Handles the Index page
 */
final class Index extends AController {

	public function show(array $params = array()) {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new Error();
		}
		// Retrieves characters from the player
		$characters = array();
		$charactersOAuth = OAuth2Users::getCharacterFromUserId();
		foreach ($charactersOAuth as $characterOAuth) {
			$esi = EsiFactory::createEsi($characterOAuth);
			$res = $esi->invoke(
				"get",
				"/characters/{character_id}/",
				array("character_id" => $characterOAuth->id_character)
			);
			// Retrieve the raw JSON
			$json = json_decode($res->raw, true);
			$characters[] = CharacterDetails::create(
				$characterOAuth->id_character,
				$json
			);

//			$res = $esi->invoke(
//				"get",
//				"/characters/{character_id}/chat_channels/",
//				array("character_id" => $character->id_character)
//			);
//			// Retrieve the raw JSON
//			$channels = json_decode($res->raw, true);
//			foreach ($channels as $channel) {
//				$this->values['channels'][$character->id_character][] = Channel::create($channel);
//			}
		}
		return new Success($characters);
	}

}
