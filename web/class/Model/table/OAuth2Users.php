<?php

namespace Model\table;

use Model\Model;

/**
 * Class OAuth2Users
 *
 * @package Model\table
 */
class OAuth2Users extends Model {

	const SCHEMA = DB_NAME;
	const TABLE = "`oauth2_users`";

	/**
	 * @var integer $id the primary ID
	 */
	public $id;

	/**
	 * @var string $access_token the access token
	 */
	public $access_token;

	/**
	 * @var string $refresh_token the refresh token
	 */
	public $refresh_token;

	/**
	 * @var integer $expire_time the expiration time
	 */
	public $expire_time;

	/**
	 * @var integer $id_character the character ID of EVE
	 */
	public $id_character;

	/**
	 * @var integer $id_forum_user the forum user ID
	 */
	public $id_forum_user;

	/**
	 * OAuth2Users constructor.
	 *
	 * @param int $id
	 * @param string $access_token
	 * @param string $refresh_token
	 * @param int $expire_time
	 * @param int $id_character
	 * @param int $id_forum_user
	 */
	public function __construct(
		$id = NULL,
		$access_token = "",
		$refresh_token = "",
		$expire_time = -1,
		$id_character = NULL,
		$id_forum_user = NULL
	) {
		parent::__construct(DB_NAME . "." . "`oauth2_users`");
		$this->pkID = "id";

		$this->id = $id;
		$this->access_token = $access_token;
		$this->refresh_token = $refresh_token;
		$this->expire_time = $expire_time;
		$this->id_character = $id_character;
		$this->id_forum_user = $id_forum_user;
	}

}