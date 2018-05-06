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
		<?= $phpbbUser->username; ?> a lié <?= \Utils\Utils::plural(count($phpbbUser->characters), "personnage"); ?>
		<?php if (!empty($phpbbUser->characters)) : ?>
		: <?php
			$characterNames = array();
			foreach ($phpbbUser->characters as $character) {
				$characterNames[] = $character->entity_name;
			}
			echo implode(", ", $characterNames);
		?>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>
<?php
	}
}
