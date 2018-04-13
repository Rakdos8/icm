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
			$this->values['characters'][] = new CharacterDetails(
				$character->id_character,
				$json['birthday'],
				$json['name'],
				$json['gender'],
				$json['description'],
				$json['race_id'],
				$json['bloodline_id'],
				$json['corporation_id'],
				array_key_exists("alliance_id", $json) ?
					$json['alliance_id'] : NULL,
				$json['ancestry_id'],
				$json['security_status'],
				array_key_exists("faction_id", $json) ?
					$json['faction_id'] : NULL
			);
		}
		return AController::TREATMENT_SUCCEED;
	}

}
