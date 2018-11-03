<?php

namespace net\bourelly\core\utils\builder;

use com\evemyadmin\controller\AController;
use net\bourelly\core\dispatcher\ADispatcher;

/**
 * Class BreadcrumbBuilder.
 *
 * @package net\bourelly\core\utils\builder
 */
class BreadcrumbBuilder implements Builder {

	public static function createBreadcrumb(): string {
		$breadcrumb = "<ol class=\"breadcrumb float-right\">";

		$page = getValueInArrayOrDefault("page", $_GET, "");
		if (!empty($page)) {
			$breadcrumb .= "<li class=\"breadcrumb-item\"><a href=\"/\">Home</a></li>";
			$action = getValueInArrayOrDefault("action", $_GET, "");
			if (!empty($action) && strcasecmp($action, AController::DEFAULT_ACTION) != 0) {
				$breadcrumb .= "<li class=\"breadcrumb-item\"><a href=\"/" . $page . "\">" . self::showInfoInBreadcrumb($page) . "</a></li>";
				$breadcrumb .= "<li class=\"breadcrumb-item active\">" . self::showInfoInBreadcrumb($action) . "</li>";
			} else if (strcasecmp($page, ADispatcher::DEFAULT_CONTROLLER) != 0) {
				$breadcrumb .= "<li class=\"breadcrumb-item active\">" . self::showInfoInBreadcrumb($page) . "</li>";
			}
		} else {
			$breadcrumb .= "<li class=\"breadcrumb-item active\">Home</li>";
		}

		return $breadcrumb . "</ol>";
	}

	private static function showInfoInBreadcrumb(string $value): string {
		return ucfirst(ucwords(str_replace('_', ' ', $value)));
	}

}
