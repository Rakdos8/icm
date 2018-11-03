<?php

namespace com\evemyadmin\pages\index\show\views;

use com\evemyadmin\utils\builder\EVEImage;
use EVEOnline\ESI\Character\CharacterDetails;
use net\bourelly\core\view\DefaultBreadcrumb;
use net\bourelly\core\view\View;

/**
 * Class Success for the show in Index controller
 *
 * @package com.evemyadmin.pages\Index\Show\Views
 */
class Success implements View {

	use DefaultBreadcrumb;

	/** @var array $characters  */
	private $characters;

	/**
	 * UpdatePhpbb constructor.
	 *
	 * @param CharacterDetails[] $characters
	 */
	public function __construct(
		array $characters
	) {
		$this->characters = $characters;
	}

	public function getPageTitle(): string {
		return "Players";
	}

	public function showHtmlTemplate() {
?>
<a href="<?= OAUTH_LOGIN_URL ?>">
	<img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="EVE SSO login">
</a>
<br>
<br>
Vous avez synchornisé <?= \net\bourelly\core\utils\Utils::plural(count($this->characters), "personnage"); ?>:<br>
<?php foreach ($this->characters as $character) : ?>
	<?php $urlPortrait = EVEImage::getCharacterImage($character->getCharacterId(), 128); ?>
	<img src="<?= $urlPortrait; ?>" alt="Portrait" title="<?= $character->getName(); ?>" class="rounded-circle">
<?php endforeach; ?>
<?php
	}

	public function getJsonTemplate() {
		return $this->characters;
	}

}
