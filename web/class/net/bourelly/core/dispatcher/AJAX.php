<?php

namespace net\bourelly\core\dispatcher;

use net\bourelly\core\utils\handler\ErrorHandler;
use net\bourelly\core\utils\Utils;
use net\bourelly\core\view\defaults\ErrorView;
use net\bourelly\core\view\View;

/**
 * Dispatcher for the AJAX
 *
 * @package net\bourelly\core\dispatcher
 */
final class AJAX extends ADispatcher {

	/**
	 * AJAX Dispatcher constructor.
	 *
	 * @param string $page the page name
	 */
	protected function __construct(string $page) {
		parent::__construct($page);
	}

	protected final function handleResponse(): View {
		try {
			$view = $this->controller->execute($this->getParameters());
		} catch (\Throwable $ex) {
			ErrorHandler::logException($ex, DEBUG);
			$view = new ErrorView($ex->getMessage());
		}
		header("Content-Type: application/json");
		$this->prepareJsonResponse($view);

		// Nothing more required, die "properly"
		die;
	}

	private function prepareJsonResponse(View $view) {
		$json = array();

		// Removes the first value in the array
		$values = $view->getJsonTemplate();

		// If the controller had an error
		if ($view instanceof ErrorView) {
			$json['state'] = "error";
			$json['error'] = $values;
		} else {
			$json['state'] = "ok";
			$json['value'] = $values;
		}
		// Modify HTML into UTF-8 elements
		array_walk_recursive($json, Utils::class . "::htmlentities_array_map");

		// Prints the JSON
		echo json_encode($json, JSON_UNESCAPED_SLASHES);
	}

}
