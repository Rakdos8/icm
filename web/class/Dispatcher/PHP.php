<?php

namespace Dispatcher;

use View\View;

/**
 * Dispatcher for the PHP
 */
final class PHP extends ADispatcher {

	protected final function handleResponse(View $view) {
		// The template will be the only one which can retrieve data
		ob_start(
			!in_array("ob_gzhandler", ob_list_handlers()) ?
				"ob_gzhandler" : NULL
		);
		$view->showTemplate();
		$ret = ob_get_clean();
		ob_end_flush();
		return $ret;
	}

}
