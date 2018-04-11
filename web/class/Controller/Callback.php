<?php

namespace Controller;

use EVEOnline\OAuth\Login;
use EVEOnline\OAuth\LoginResourceOwner;
use Utils\Utils;

/**
 * Handles the Index page
 */
final class Callback extends AController {

	public function show($params = array()) {
		return AController::TREATMENT_ERROR;
	}

	/**
	 * Called when the SSO login is done by a user.
	 *
	 * @param array $params
	 * @return string state of the controller
	 */
	public function login($params = array()) {
		// The code was not given, might be a manual call to this page.
		if (!array_key_exists("code", $params)) {
			Utils::redirect("/");
		}
		$code = $params['code'];
		// The code was empty, might be a manual call to this page.
		if (empty($code)) {
			Utils::redirect("/");
		}

		$tokenRetriever = new Login(
			array(
				"clientId" => ESI_CLIENT_ID,
				"clientSecret" => ESI_SECRET_KEY,
				"redirectUri" => urlencode(ESI_CALLBACK_URL)
			)
		);
		echo "<hr>";
		$token = $tokenRetriever->getAccessToken("authorization_code", array("code" => $code));

		// Optional: Now you have a token you can look up a users profile data
		try {
			// We got an access token, let's now get the user's details
			$user = $tokenRetriever->getResourceOwner($token);
			// Use these details to create a new profile
			printf('Hello %s!', $user->getCharacterName());
			die;
		} catch (\Exception $e) {
			// Failed to get user details
			debug($e);
			exit('Oh dear...: ' . $e->getMessage());
		}
		Utils::redirect("/");
		return AController::TREATMENT_SUCCEED;
	}

}
