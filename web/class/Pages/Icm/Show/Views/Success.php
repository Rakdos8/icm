<?php

namespace Pages\Icm\Show\Views;

use Model\Bean\PhpBBUsers;
use View\DefaultBreadcrumb;
use View\View;

/**
 * Class Success for the show in ICM controller
 *
 * @package Pages\Icm\Show\Views
 */
class Success implements View {

	use DefaultBreadcrumb;

	/** @var PhpBBUsers[] $phpbbUsers  */
	private $phpbbUsers;

	/**
	 * Show ICM View success constructor.
	 *
	 * @param PhpBBUsers[] $phpbbUsers
	 */
	public function __construct(
		array $phpbbUsers
	) {
		$this->phpbbUsers = $phpbbUsers;
	}

	public function getPageTitle() {
		return "PhpBB Users";
	}

	public function showTemplate() {
?>
Il y a <?= \Utils\Utils::plural(count($this->phpbbUsers), "utilisateur"); ?>:<br>
<ul>
<?php foreach ($this->phpbbUsers as $phpbbUser) : ?>
	<li>
		<a href="<?= PHPBB_URL ?>/memberlist.php?mode=viewprofile&u=<?= $phpbbUser->user_id; ?>"><?= $phpbbUser->username; ?></a>
		a lié <?= \Utils\Utils::plural(count($phpbbUser->characters), "personnage"); ?>
		<?php foreach ($phpbbUser->characters as $character) : ?>
			<a href="/callback/change-character/<?= $character->id_entity; ?>"><?= $character->entity_name; ?></a>,
		<?php endforeach; ?>
	</li>
<?php endforeach; ?>
</ul>
<?php
	}
}
