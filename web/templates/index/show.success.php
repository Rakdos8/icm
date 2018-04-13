<a href="<?= ESI_LOGIN_BASE_URL ?>/oauth/authorize?response_type=code&redirect_uri=<?= urlencode(FULL_DOMAIN . "/" . ESI_CALLBACK_URL); ?>&client_id=<?= ESI_CLIENT_ID ?>&scope=characterMailRead characterAccountRead">
	<img src="https://web.ccpgamescdn.com/eveonlineassets/developers/eve-sso-login-black-large.png" alt="EVE SSO login">
</a>
<br>
<br>
Vous avez synchornisé <?= \Utils\Utils::plural(count($values), "personnage"); ?>:<br>
<?php foreach ($values as $character) : ?>
<a href="#">
	<img src="<?= IMAGE_SERVER_URL . "/Character/" . $character->id_character . "_128.jpg"; ?>" alt="portrait" style="border-radius: 50%;">
</a>
<?php endforeach; ?>