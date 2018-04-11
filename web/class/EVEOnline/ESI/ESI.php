<?php

namespace EVEOnline\ESI;

/**
 * Main class which handles HTTP query, login, and other stuff.
 *
 * @package EVEOnline\ESI
 */
class ESI {

	/**
	 * @var string the client ID of the ESI application
	 */
	protected $clientId;

	/**
	 * @var string the secret key of the ESI application
	 */
	protected $secretKey;

	/**
	 * @var string User-Agent in HTTP header to give contact in case of error for EVE devs
	 */
	private $userAgent = "User-Agent: EVEMyAdmin (Beta). Contact Freyers In-Game";

	/**
	 * ESI constructor.
	 *
	 * @param string $clientId the client ID
	 * @param string $secretKey the secret key
	 */
	public function __construct(
		$clientId = ESI_CLIENT_ID,
		$secretKey = ESI_SECRET_KEY
	) {
		$this->clientId = $clientId;
		$this->secretKey = $secretKey;
	}

	/**
	 * Builds the HTTP request as a resource.
	 *
	 * @param string $httpType the HTTP connection type
	 * @param array $headers array of custom other (HTTP) headers
	 * @return array the HTTP headers and content to send, user agent on the right HTTP connection type
	 */
	protected function buildHttpHeader(
		$httpType = HttpConnection::GET,
		$headers = array()
	) {
		return array(
			"https" => array(
				"method" => $httpType,
				"header" => array_merge($headers, array($this->userAgent))
			)
		);
	}

}