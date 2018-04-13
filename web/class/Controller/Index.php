<?php

namespace Controller;

use Model\table\OAuth2Users;

/**
 * Handles the Index page
 */
final class Index extends AController {

	public function show($params = array()) {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return AController::TREATMENT_ERROR;
		}
		// Retrieves characters from the player
		$this->values['characters'] = OAuth2Users::getCharacterFromUserId();
		return AController::TREATMENT_SUCCEED;
	}

}
