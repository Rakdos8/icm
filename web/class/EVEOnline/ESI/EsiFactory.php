<?php

namespace EVEOnline\ESI;

use Model\table\OAuth2Users;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

/**
 * Main class which handles HTTP query, login, and other stuff.
 *
 * @package EVEOnline\ESI
 */
class EsiFactory {

	/** @var string User-Agent in HTTP header to give contact in case of error for EVE developers */
	private static $USER_AGENT = "User-Agent: EVEMyAdmin (Beta). Contact Freyers In-Game";

	/** @var Configuration the ESI configuration (user agent, etc) */
	private static $CONFIGURATION = null;

	/** @var EsiAuthentication[] the ESI authentication by character id */
	private static $AUTHENTICATIONS = array();

	/** @var Eseye[] the ESI handler by character id */
	private static $ESEYES = array();

	/**
	 * Creates an Eseye connection.
	 *
	 * @param OAuth2Users $oauthUser the OAuth2Users user
	 * @return Eseye the ESI connection
	 * @throws \Seat\Eseye\Exceptions\InvalidContainerDataException
	 */
	public static function createEsi(
		OAuth2Users $oauthUser
	) {
		if (is_null($oauthUser)) {
			throw new \InvalidArgumentException("You must provide a character.");
		}

		// Prepares the configuration of ESI
		if (is_null(self::$CONFIGURATION)) {
			self::$CONFIGURATION = Configuration::getInstance();
			self::$CONFIGURATION->http_user_agent = self::$USER_AGENT;
			self::$CONFIGURATION->logfile_location = PATH_ESI_LOG;
			self::$CONFIGURATION->file_cache_location = PATH_ESI_CACHE;
		}

		// Creates an authentication for ESI if not done yet
		$idCharacter = $oauthUser->id_character;
		if (!array_key_exists($idCharacter, self::$AUTHENTICATIONS)) {
			self::$AUTHENTICATIONS[$idCharacter] = new EsiAuthentication(
				array(
					"client_id" => ESI_CLIENT_ID,
					"secret" => ESI_SECRET_KEY,
					"refresh_token" => $oauthUser->refresh_token
				)
			);
		}

		if (!array_key_exists($idCharacter, self::$ESEYES)) {
			self::$ESEYES[$idCharacter] = new Eseye(self::$AUTHENTICATIONS[$idCharacter]);
		}
		// Creates the connection
		return self::$ESEYES[$idCharacter];
	}

}