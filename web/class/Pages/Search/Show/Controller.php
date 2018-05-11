<?php

namespace Pages\Search\Show;

use Controller\AController;
use Seat\Eseye\Eseye;
use Utils\Utils;
use View\DebugView;
use View\View;

/**
 * Handles the show action in Search page
 *
 * @package Pages\Search\Show
 */
final class Controller extends AController {

	public function execute(array $params = array()): View {
		if (empty($params)) {
			Utils::redirect("/");
			// return Error !
		}

		// No authentication required for a simple search
		$esi = new Eseye();
		// Sets the parameters
		$esi->setQueryString(
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
		$res = $esi->invoke(
			"get",
			"/search/"
		);
		// Retrieve the raw JSON
		$json = json_decode($res->raw, true);
		return new DebugView($json);
	}

}
