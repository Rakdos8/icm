<?php

namespace com\evemyadmin\pages\callback\login;

use com\evemyadmin\controller\AController;
use com\evemyadmin\model\bean\OAuth2Users;
use EVEOnline\OAuth\Login;
use net\bourelly\core\utils\Utils;
use net\bourelly\core\view\View;

/**
 * Handles the login action on the Callback page
 *
 * @package com.evemyadmin.pages\Callback\Login
 */
final class Controller extends AController {

	/**
	 * Called when the SSO login is done by a user.
	 *
	 * @param array $params
	 * @return View the view, will always be null
	 */
	public function execute(array $params = array()): View {
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
		$token = $tokenRetriever->getAccessToken("authorization_code", array("code" => $code));

		// We got an access token, let's now get the user's details
		$user = $tokenRetriever->getResourceOwner($token);
		// Save in DataBase access + refresh tokens and character
		$oauthUser = new OAuth2Users();
		$oauthUser->access_token = $token->getToken();
		$oauthUser->refresh_token = $token->getRefreshToken();
		$oauthUser->expire_time = $token->getExpires();
		$oauthUser->token_type = $user->getTokenType();
		$oauthUser->id_entity = $user->getId();
		$oauthUser->id_forum_user = parent::getPhpbbHandler()->getUser()->data['user_id'];
		$oauthUser->entity_name = $user->getName();
		$oauthUser->is_main_character = empty($this->charactersOAuth) ? true : false;

		$oauthUser->insert();
		Utils::redirect("/");
		return NULL;
	}

}
