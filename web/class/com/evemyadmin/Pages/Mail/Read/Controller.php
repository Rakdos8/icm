<?php

namespace com\evemyadmin\pages\mail\read;

use com\evemyadmin\controller\AController;
use com\evemyadmin\pages\mail\read\views\Success;
use com\evemyadmin\view\errors\NoActiveCharacterError;
use com\evemyadmin\view\errors\NotConnectedForumError;
use EVEOnline\ESI\EsiFactory;
use EVEOnline\ESI\Mail\MailBody;
use EVEOnline\ESI\Mail\MailLabel;
use EVEOnline\ESI\Mail\MailList;
use EVEOnline\ESI\Utils\EntitiesRetriever;
use EVEOnline\ESI\Utils\Enums\EntityType;
use EVEOnline\ESI\Utils\SimpleEntityInfo;
use net\bourelly\core\utils\handler\ErrorHandler;
use net\bourelly\core\utils\Utils;
use net\bourelly\core\view\defaults\errors\MissingInformation;
use net\bourelly\core\view\View;
use Seat\Eseye\Exceptions\RequestFailedException;

/**
 * Handles the read action in Mail page
 *
 * @package com.evemyadmin.pages\Mail\Read
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

		// Retrieves mails from the active character
		$currentUser = $this->session->getActiveCharacter()->getOauthUser();

		try {
			$res = EsiFactory::invoke(
				$currentUser,
				"get",
				"/characters/{character_id}/mail/{mail_id}/",
				array(
					"character_id" => $currentUser->id_entity,
					"mail_id" => intval($params[0])
				)
			);
		} catch (RequestFailedException $ex) {
			// No mail found on this character, return to mail inbox
			ErrorHandler::logException($ex, DEBUG);
			Utils::redirect("/mail/");
			die;
		}
		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);

		$mailBody = MailBody::create($json);
		$mailBody->setMailId(intval($params[0]));
		// Retrieve recipients ID to attach name
		$recipients = array(
			$mailBody->getFrom() => new SimpleEntityInfo(
					$mailBody->getFrom(),
					"",
					new EntityType(EntityType::CHARACTER)
				)
		);
		foreach ($mailBody->getRecipients() as $recipient) {
			$recipients[$recipient->getRecipientId()] = new SimpleEntityInfo(
				$recipient->getRecipientId(),
				"",
				new EntityType($recipient->getRecipientType())
			);
		}

		// If there is character ID recipient
		if (!empty($recipients)) {
			EntitiesRetriever::getEntityInfo($recipients);
			$mailBody->setFromName($recipients[$mailBody->getFrom()]->getName());
			foreach ($mailBody->getRecipients() as $recipient) {
				$recipient->setRecipientName($recipients[$recipient->getRecipientId()]->getName());
			}
		}

		$isOwnCharacter = true;
		// Check if it's a director's character
		if ($this->getPhpbbHandler()->isDirector()) {
			$isOwnCharacter = false;
			foreach ($this->charactersOAuth as $oauth2User) {
				if ($oauth2User->id_entity == $currentUser->id_entity) {
					$isOwnCharacter = true;
					break;
				}
			}
		}
		return new Success(
			MailLabel::invoke($currentUser),
			MailList::invoke($currentUser),
			$mailBody,
			$isOwnCharacter
		);
	}

}
