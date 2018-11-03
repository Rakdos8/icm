<?php

namespace com\evemyadmin\pages\search\show;

use com\evemyadmin\controller\AController;
use EVEOnline\ESI\EsiFactory;
use net\bourelly\core\utils\Utils;
use net\bourelly\core\view\defaults\debug\DebugView;
use net\bourelly\core\view\View;

/**
 * Handles the show action in Search page
 *
 * @package com.evemyadmin.pages\Search\Show
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if (empty($params)) {
			Utils::redirect("/");
		}

		$res = EsiFactory::invoke(
			null,
			"get",
			"/search/",
			array(),
			array(
				"search" => trim($params[0]),
				"strict" => false,
				"categories" => array(
					"agent",
					"alliance",
					"character",
					"constellation",
					"corporation",
					"faction",
					"inventory_type",
					"region",
					"solar_system",
					"station"
				)
			)
		);
		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		return new DebugView($json);
	}

}
