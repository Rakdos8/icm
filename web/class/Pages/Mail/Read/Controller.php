<?php

namespace Pages\Mail\Read;

use Controller\AController;
use EVEOnline\ESI\EsiFactory;
use EVEOnline\ESI\Mail\MailBody;
use EVEOnline\ESI\Mail\MailLabel;
use EVEOnline\ESI\Mail\MailList;
use EVEOnline\ESI\Utils\EntitiesRetriever;
use EVEOnline\ESI\Utils\Enums\EntityType;
use EVEOnline\ESI\Utils\SimpleEntityInfo;
use Pages\Mail\Read\Views\Success;
use Seat\Eseye\Exceptions\RequestFailedException;
use Utils\Handler\ErrorHandler;
use Utils\Utils;
use View\Errors\MissingInformation;
use View\Errors\NoActiveCharacterError;
use View\Errors\NotConnectedForumError;
use View\View;

/**
 * Handles the read action in Mail page
 *
 * @package Pages\Mail\Read
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
		$oauthUser = $this->session->getActiveCharacter()->getOauthUser();

		$esi = EsiFactory::createEsi($oauthUser);
		try {
			$res = $esi->invoke(
				"get",
				"/characters/{character_id}/mail/{mail_id}/",
				array(
					"character_id" => $oauthUser->id_entity,
					"mail_id" => intval($params[0])
				)
			);
		}
		// No mail found on this character, return to mail inbox
		catch (RequestFailedException $ex) {
			ErrorHandler::logException($ex, true);
			Utils::redirect("/mail/");
		}
		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);

		$mailBody = MailBody::create($json);
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
		return new Success(
			MailLabel::invoke($oauthUser),
			MailList::invoke($oauthUser),
			$mailBody
		);
	}

}
