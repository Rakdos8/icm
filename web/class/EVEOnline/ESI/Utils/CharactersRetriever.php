<?php

namespace EVEOnline\ESI\Utils;
use Seat\Eseye\Eseye;

/**
 * Helps to retrieve character names from their ID.
 *
 * @package EVEOnline\ESI\Utils
 */
class CharactersRetriever {

	/** @var SimpleCharacterInfo[] */
	private static $CHARACTER_INFO = array();

	/**
	 * @param array $characterIds the character IDs as an array
	 * @return SimpleCharacterInfo[] the SimpleCharacterInfo array or empty
	 */
	public static function getCharacterInfo(
		array $characterIds
	): array {
		// Remove duplicates
		$characterIds = array_unique($characterIds);

		$idsToFetch = array();
		foreach ($characterIds as $characterId) {
			// Forget if the character was already added
			if (array_key_exists($characterId, self::$CHARACTER_INFO)) {
				continue;
			}
			$idsToFetch[] = intval($characterId);
		}

		if (!empty($idsToFetch)) {
			$esi = new Eseye();
			// Sets the parameters
			$esi->setQueryString(array("character_ids" => $idsToFetch));
			$res = $esi->invoke(
				"get",
				"/characters/names/"
			);
			$json = json_decode($res->raw, true);
			foreach ($json as $character) {
				$character = SimpleCharacterInfo::create($character);
				self::$CHARACTER_INFO[$character->getCharacterId()] = $character;
			}
		}

		$characters = array();
		foreach ($characterIds as $characterId) {
			$characters[$characterId] = self::$CHARACTER_INFO[$characterId];
		}
		return $characters;
	}

}
