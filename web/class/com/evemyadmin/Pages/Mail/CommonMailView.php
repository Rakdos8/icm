<?php

namespace com\evemyadmin\pages\mail;

use net\bourelly\core\view\EmptyBreadcrumb;
use net\bourelly\core\view\EmptyPageTitle;
use net\bourelly\core\view\View;

/**
 * Class CommonMailView
 *
 * @package com.evemyadmin.pages\Mail
 */
class CommonMailView implements View {

	use EmptyPageTitle;
	use EmptyBreadcrumb;

	public function showHtmlTemplate() {
?>
<!-- Notifications plugin -->
<script src="/plugins/notifyjs/dist/notify.min.js"></script>
<script src="/plugins/notifications/notify-metro.js"></script>
<script src="/js/mailManagement.js"></script>
<?php
	}

	public function getJsonTemplate() {
		return "";
	}

}
