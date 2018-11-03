<?php

namespace com\evemyadmin\pages\ajax\browse_label;

use com\evemyadmin\controller\AController;
use com\evemyadmin\view\errors\NoActiveCharacterError;
use com\evemyadmin\view\errors\NotConnectedForumError;
use EVEOnline\ESI\EsiFactory;
use EVEOnline\ESI\Utils\EntitiesRetriever;
use EVEOnline\ESI\Utils\Enums\EntityType;
use EVEOnline\ESI\Utils\SimpleEntityInfo;
use net\bourelly\core\view\defaults\debug\DebugView;
use net\bourelly\core\view\defaults\errors\MissingInformation;
use net\bourelly\core\view\View;

/**
 * Handles browsing character's mail label
 *
 * @package com.evemyadmin.pages\Ajax\Browse_label
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if ($this->getPhpbbHandler()->isAnonymous()) {
			return new NotConnectedForumError();
		} else if (is_null($this->session->getActiveCharacter())) {
			return new NoActiveCharacterError();
		} else if (empty($params) || !is_numeric($params[0])) {
			return new MissingInformation();
		}

		// Retrieves characters from the player
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();
		$res = EsiFactory::invoke(
			$oauthUser,
			"get",
			"/characters/{character_id}/mail/",
			array("character_id" => $oauthUser->id_entity),
			array("labels" => array(intval($params[0])))
		);

		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		$entities = array();
		foreach ($json as $mail) {
			$entities[$mail['from']] = new SimpleEntityInfo(
				$mail['from'],
				"",
				new EntityType(EntityType::CHARACTER)
			);
		}

		// Injects the character name in the returned JSON
		EntitiesRetriever::getEntityInfo($entities);
		foreach ($json as &$mail) {
			$mail['from_name'] = $entities[$mail['from']]->getName();
		}

		return new DebugView($json);
	}

}
