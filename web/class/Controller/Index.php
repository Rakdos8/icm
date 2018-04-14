<?php

namespace Controller;

use EVEOnline\ESI\Character\CharacterDetails;
use EVEOnline\ESI\EsiFactory;
use Model\table\OAuth2Users;

/**
 * Handles the Index page
 */
final class Index extends AController {

	public function show($params = array()) {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return AController::TREATMENT_ERROR;
		}
		// Retrieves characters from the player
		$characters = OAuth2Users::getCharacterFromUserId();
		foreach ($characters as $character) {
			$esi = EsiFactory::createEsi($character);
			$res = $esi->invoke(
				"get",
				"/characters/{character_id}/",
				array("character_id" => $character->id_character)
			);
			// Retrieve the raw JSON
			$json = json_decode($res->raw, true);
			$this->values['characters'][] = CharacterDetails::create(
				$character->id_character,
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
		return AController::TREATMENT_SUCCEED;
	}

}
