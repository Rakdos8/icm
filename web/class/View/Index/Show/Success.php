<?php

namespace View\Index\Show;

use EVEOnline\ESI\Character\CharacterDetails;
use View\View;

/**
 * Class Success for the show in Index controller
 *
 * @package View\Index\Show
 */
class Success implements View {

	/** @var array $characters  */
	private $characters;

	/**
	 * Success constructor.
	 *
	 * @param CharacterDetails[] $characters
	 */
	public function __construct(
		array $characters
	) {
		$this->characters = $characters;
	}

	public function showTemplate() {
?>
<a href="<?= ESI_LOGIN_BASE_URL ?>/oauth/authorize?response_type=code&redirect_uri=<?= urlencode(FULL_DOMAIN . "/" . ESI_CALLBACK_URL); ?>&client_id=<?= ESI_CLIENT_ID ?>&scope=characterMailRead characterAccountRead characterChatChannelsRead esi-characters.read_chat_channels.v1">
	<img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="EVE SSO login">
</a>
<br>
<br>
Vous avez synchornisé <?= \Utils\Utils::plural(count($this->characters), "personnage"); ?>:<br>
<?php foreach ($this->characters as $character) : ?>
	<?php $urlPortrait = IMAGE_SERVER_URL . "/Character/" . $character->getCharacterId() . "_128.jpg"; ?>
	<a href="#">
		<img src="<?= $urlPortrait; ?>" alt="Portrait" title="<?= $character->getName(); ?>" style="border-radius: 50%;">
	</a>
<?php endforeach; ?>
<?php
	}
}