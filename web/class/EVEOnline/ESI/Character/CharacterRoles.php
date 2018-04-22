<?php

namespace EVEOnline\ESI\Character;

/**
 * Class CharacterRoles
 *
 * @package EVEOnline\ESI\Character
 */
final class CharacterRoles {

	/**
	 * @var array global roles in corporation
	 */
	private $roles;

	/**
	 * @var array roles at HQ
	 */
	private $rolesAtHq;

	/**
	 * @var array roles at base
	 */
	private $rolesAtBase;

	/**
	 * @var array other roles
	 */
	private $rolesAtOther;

	/**
	 * CharacterDetails constructor.
	 *
	 * @param array $roles
	 * @param array $rolesAtHq
	 * @param array $rolesAtBase
	 * @param array $rolesAtOther
	 */
	public function __construct(
		array $roles,
		array $rolesAtHq,
		array $rolesAtBase,
		array $rolesAtOther
	) {
		$this->roles = $roles;
		$this->rolesAtHq = $rolesAtHq;
		$this->rolesAtBase = $rolesAtBase;
		$this->rolesAtOther = $rolesAtOther;
	}

	/**
	 * @return array
	 */
	public function getRoles(): array {
		return $this->roles;
	}

	/**
	 * @return array
	 */
	public function getRolesAtHq(): array {
		return $this->rolesAtHq;
	}

	/**
	 * @return array
	 */
	public function getRolesAtBase(): array {
		return $this->rolesAtBase;
	}

	/**
	 * @return array
	 */
	public function getRolesAtOther(): array {
		return $this->rolesAtOther;
	}

	/**
	 * Creates a CharacterRoles from the associative json array.
	 *
	 * @param array $json the json associative array
	 * @return CharacterRoles
	 */
	public static function create(array $json) {
		return new CharacterRoles(
			$json['roles'],
			$json['roles_at_hq'],
			$json['roles_at_base'],
			$json['roles_at_other']
		);
	}

}
