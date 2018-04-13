<?php

namespace Controller;

/**
 * Handles the Index page
 */
final class Index extends AController {

	public function show($params = array()) {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return AController::TREATMENT_ERROR;
		}
		return AController::TREATMENT_SUCCEED;
	}

}
