<?php

namespace com\evemyadmin\pages\icm\show\views;

use com\evemyadmin\model\bean\PhpBBUsers;
use net\bourelly\core\utils\Utils;
use net\bourelly\core\view\DefaultBreadcrumb;
use net\bourelly\core\view\View;

/**
 * Class Success for the show in ICM controller
 *
 * @package com.evemyadmin.pages\Icm\Show\Views
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

	public function getPageTitle(): string {
		return "PhpBB Users";
	}

	public function showHtmlTemplate() {
?>
Il y a <?= Utils::plural(count($this->phpbbUsers), "utilisateur"); ?>:<br>
<div class="card-box table-responsive">
	<table id="datatable-phpbb-users" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
		<tr>
			<th>User Name</th>
			<th>Last visit</th>
			<th># EVE Character</th>
			<th>EVE Characters</th>
		</tr>
		</thead>

		<tbody>
<?php foreach ($this->phpbbUsers as $phpbbUser) : ?>
			<tr>
				<td>
					<a href="<?= PHPBB_URL ?>/memberlist.php?mode=viewprofile&u=<?= $phpbbUser->user_id; ?>"><?= $phpbbUser->username; ?></a>
				</td>
				<td>
	<?= $phpbbUser->user_lastvisit <= 0 ? "" : Utils::formatDate($phpbbUser->user_lastvisit, true); ?>
				</td>
				<td>
	<?= count($phpbbUser->characters); ?>
				</td>
				<td>
	<?php
	$i = 0;
	foreach ($phpbbUser->characters as $character) {
		$i++;
		$sep = count($phpbbUser->characters) == $i ? "" : ", ";
	?>
					<a href="/callback/change-character/<?= $character->id_entity; ?>"><?= $character->entity_name; ?></a><?= $sep; ?>
	<?php
	}
	?>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>

<link href="/plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
<link href="/plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css">
<!-- DataTable core include -->
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Plugin for DataTables -->
<script src="/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="/plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="/plugins/datatables/jszip.min.js"></script>
<script src="/plugins/datatables/pdfmake.min.js"></script>
<script src="/plugins/datatables/buttons.html5.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		// Creates a datatable
		var table = $("#datatable-phpbb-users").DataTable({
			// Allows to change the number of entry per page
			lengthChange: true,
			// Add button for export purpose
			buttons: ['copy', 'excel', 'pdf']
		});
		table.buttons().container()
			.appendTo('#datatable-phpbb-users_wrapper .col-md-6:eq(0)');
	} );
</script>
<?php
	}

	public function getJsonTemplate() {
		return $this->phpbbUsers;
	}
}
