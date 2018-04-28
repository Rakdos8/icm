<?php

namespace Controller;

use EVEOnline\ESI\Character\Channel;
use EVEOnline\ESI\Character\CharacterDetails;
use EVEOnline\ESI\EsiFactory;
use Seat\Eseye\Exceptions\EsiScopeAccessDeniedException;

/**
 * Handles the Index page
 */
final class Index extends AController {

	public function show(array $params = array()) {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new \View\Index\Show\Error();
		}

		// Retrieves characters from the player
		$characters = array();
		foreach ($this->charactersOAuth as $character) {
			$esi = EsiFactory::createEsi($character);
			//TODO: Handles properly the API lost
			$res = $esi->invoke(
				"get",
				"/characters/{character_id}/",
				array("character_id" => $character->id_character)
			);

			// Retrieve the raw JSON
			$json = json_decode($res->raw, true);
			$characters[] = CharacterDetails::create(
				$character->id_character,
				$json
			);
		}
		return new \View\Index\Show\Success($characters);
	}

	public function channels(array $params = array()) {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new \View\Index\Show\Error();
		}

		// Retrieves characters from the player
		$activeCharacter = $this->session->getActiveCharacter();
		if (is_null($activeCharacter)) {
			return new \View\Index\Channels\Error("Vous devez sélectionner un personnage");
		}

		$esi = EsiFactory::createEsi($activeCharacter->getOauthUser());
		try {
			// Thanks to CCP, it will be removed on 18th May 2018
			// https://github.com/ccpgames/esi-issues/commit/cfb95bad543de779354abd51c66bf01252a732fb
			$esi->setVersion("v1");
			$res = $esi->invoke(
				"get",
				"/characters/{character_id}/chat_channels/",
				array("character_id" => $activeCharacter->getCharacterId())
			);
		} catch (EsiScopeAccessDeniedException $ex) {
			return new \View\Index\Channels\Error("Vous n'avez pas autorisé la lecture des channels depuis l'ESI");
		}
		// Retrieve the raw JSON
		$channels = array();
		$rawChannels = json_decode($res->raw, true);
		foreach ($rawChannels as $rawChannel) {
			$channels[] = Channel::create($rawChannel);
		}
		return new \View\Index\Channels\Success($channels);
	}

}
