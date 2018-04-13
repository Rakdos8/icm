<?php

namespace EVEOnline\ESI;

use Model\table\OAuth2Users;
use Seat\Eseye\Configuration;
use Seat\Eseye\Containers\EsiAuthentication;
use Seat\Eseye\Eseye;

/**
 * Main class which handles HTTP query, login, and other stuff.
 *
 * @package EVEOnline\EsiFactory
 */
class EsiFactory {

	/**
	 * @var string User-Agent in HTTP header to give contact in case of error for EVE devs
	 */
	const USER_AGENT = "User-Agent: EVEMyAdmin (Beta). Contact Freyers In-Game";

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
		// Set specific configuration
		$configuration = Configuration::getInstance();
		$configuration->http_user_agent = self::USER_AGENT;
		$configuration->logfile_location = PATH_ESI_LOG;
		$configuration->file_cache_location = PATH_ESI_CACHE;

		// Creates an authentication for ESI
		$authentication = new EsiAuthentication(
			array(
				"client_id" => ESI_CLIENT_ID,
				"secret" => ESI_SECRET_KEY,
				"refresh_token" => $oauthUser->refresh_token
			)
		);
		// Creates the connection
		return new Eseye($authentication);
	}

}