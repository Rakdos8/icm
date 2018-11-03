<?php

namespace com\evemyadmin\pages\icm\show;

use com\evemyadmin\controller\AController;
use com\evemyadmin\model\bean\OAuth2Users;
use com\evemyadmin\model\bean\PhpBBUsers;
use com\evemyadmin\pages\icm\show\views\Success;
use net\bourelly\core\utils\Utils;
use net\bourelly\core\view\View;

/**
 * Handles the show action in Index page
 *
 * @package com.evemyadmin.pages\Index\Show
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if (!$this->getPhpbbHandler()->isDirector()) {
			Utils::redirect("/");
		}

		$phpbbUsers = PhpBBUsers::getAllCharacters();
		foreach ($phpbbUsers as &$phpbbUser) {
			$phpbbUser->characters = OAuth2Users::getCharacterFromUserId($phpbbUser->user_id);
		}
		return new Success($phpbbUsers);
	}

}
