<?php

namespace net\bourelly\core\view\defaults\debug;

use net\bourelly\core\utils\builder\BreadcrumbBuilder;
use net\bourelly\core\view\EmptyPageTitle;
use net\bourelly\core\view\View;

/**
 * Class Success for the show in Index controller
 *
 * @package net\bourelly\core\view\defaults\debug
 */
class DebugView implements View {

	use EmptyPageTitle;

	/** @var mixed $values  */
	private $values;

	/** @var bool $shouldDie if the PHP script should end after shown */
	private $shouldDie;

	public function __construct(
		$values,
		$shouldDie = false
	) {
		$this->values = $values;
		$this->shouldDie = $shouldDie;
	}

	public function getBreadcrumb(): string {
		return BreadcrumbBuilder::createBreadcrumb();
	}

	public function showHtmlTemplate() {
		debug($this->values, $this->shouldDie);
	}

	public function getJsonTemplate() {
		return $this->values;
	}

}
