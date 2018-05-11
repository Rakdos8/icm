<?php

namespace EVEOnline\ESI\Utils;

/**
 * Stores simple information of the character: ID and name.
 *
 * @package EVEOnline\ESI\Utils
 */
class SimpleCharacterInfo {

	/** @var int */
	private $characterId;

	/** @var string */
	private $name;

	/**
	 * SimpleCharacterInfo constructor.
	 *
	 * @param int $characterId
	 * @param string $name
	 */
	private function __construct(int $characterId, string $name) {
		$this->characterId = $characterId;
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getCharacterId(): int {
		return $this->characterId;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Creates a SimpleCharacterInfo from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return SimpleCharacterInfo
	 */
	public static function create(array $json) {
		return new SimpleCharacterInfo(
			$json['character_id'],
			$json['character_name']
		);
	}

}
