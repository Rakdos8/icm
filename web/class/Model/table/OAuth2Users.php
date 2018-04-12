<?php

namespace Model\table;

use Model\Model;
use Model\MySQL;
use Utils\Handler\PhpBB;

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
	public $id = NULL;

	/**
	 * @var string $access_token the access token
	 */
	public $access_token = "";

	/**
	 * @var string $refresh_token the refresh token
	 */
	public $refresh_token = "";

	/**
	 * @var integer $expire_time the expiration time
	 */
	public $expire_time = -1;

	/**
	 * @var integer $id_character the character ID of EVE
	 */
	public $id_character = NULL;

	/**
	 * @var integer $id_forum_user the forum user ID
	 */
	public $id_forum_user = NULL;

	/**
	 * OAuth2Users constructor.
	 */
	public function __construct() {
		parent::__construct(
			self::SCHEMA . "." . self::TABLE,
			array("id_character", "id_forum_user"),
			"id"
		);
	}

	/**
	 * Retrieves the OAuth2Users from the given user id (forum user).
	 *
	 * @param integer $userId the forum user ID (by default the connected user)
	 * @return OAuth2Users[] Every registered character
	 */
	public static function getCharacterFromUserId($userId = NULL) {
		if (is_null($userId)) {
			$userId = PhpBB::getInstance()->getUser()->data['user_id'];
		}

		if (!is_numeric($userId) || $userId <= 0) {
			return array();
		}

		$sqlQuery = "
			SELECT
				*
			FROM
				" . self::SCHEMA . "." . self::TABLE . "
			WHERE
				`id_forum_user` = ?
			;";
		$db = new MySQL();
		return $db->objExec($sqlQuery, __CLASS__, array($userId));
	}

}