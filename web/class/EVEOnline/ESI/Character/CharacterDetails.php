<?php

namespace EVEOnline\ESI\Character;

/**
 * Class CharacterDetails
 *
 * @package EVEOnline\ESI\Character
 */
final class CharacterDetails {

	/**
	 * @var integer
	 */
	private $characterId;

	/**
	 * @var string
	 */
	private $birthday;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $gender;

	/**
	 * @var string
	 */
	private $biography;

	/**
	 * @var integer
	 */
	private $raceId;

	/**
	 * @var integer
	 */
	private $bloodLineId;

	/**
	 * @var integer
	 */
	private $corporationId;

	/**
	 * @var integer
	 */
	private $allianceId;

	/**
	 * @var integer
	 */
	private $ancestryId;

	/**
	 * @var double
	 */
	private $securityStatus;

	/**
	 * @var integer
	 */
	private $factionId;

	/**
	 * CharacterDetails constructor.
	 * @param int $characterId
	 * @param string $birthday
	 * @param string $name
	 * @param string $gender
	 * @param string $biography
	 * @param int $raceId
	 * @param int $bloodLineId
	 * @param int $corporationId
	 * @param int $allianceId
	 * @param int $ancestryId
	 * @param float $securityStatus
	 * @param int $factionId
	 */
	public function __construct(
		$characterId,
		$birthday,
		$name,
		$gender,
		$biography,
		$raceId,
		$bloodLineId,
		$corporationId,
		$allianceId,
		$ancestryId,
		$securityStatus,
		$factionId
	) {
		$this->characterId = $characterId;
		$this->birthday = $birthday;
		$this->name = $name;
		$this->gender = $gender;
		$this->biography = $biography;
		$this->raceId = $raceId;
		$this->bloodLineId = $bloodLineId;
		$this->corporationId = $corporationId;
		$this->allianceId = $allianceId;
		$this->ancestryId = $ancestryId;
		$this->securityStatus = $securityStatus;
		$this->factionId = $factionId;
	}

	/**
	 * @return int
	 */
	public function getCharacterId() {
		return $this->characterId;
	}

	/**
	 * @return string
	 */
	public function getBirthday() {
		return $this->birthday;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @return string
	 */
	public function getBiography() {
		return $this->biography;
	}

	/**
	 * @return int
	 */
	public function getRaceId() {
		return $this->raceId;
	}

	/**
	 * @return int
	 */
	public function getBloodLineId() {
		return $this->bloodLineId;
	}

	/**
	 * @return int
	 */
	public function getCorporationId() {
		return $this->corporationId;
	}

	/**
	 * @return int
	 */
	public function getAllianceId() {
		return $this->allianceId;
	}

	/**
	 * @return int
	 */
	public function getAncestryId() {
		return $this->ancestryId;
	}

	/**
	 * @return float
	 */
	public function getSecurityStatus() {
		return $this->securityStatus;
	}

	/**
	 * @return boolean
	 */
	public function isInFactionalWarfare() {
		return !is_null($this->factionId);
	}

	/**
	 * @return int
	 */
	public function getFactionId() {
		return $this->factionId;
	}

}