<?php

namespace net\bourelly\core\view;

/**
 * Interface view
 *
 * @package net\bourelly\core\view
 */
interface View {

	/**
	 * Retrieves the page title.
	 *
	 * @return string the current page to be printed
	 */
	public function getPageTitle(): string;

	/**
	 * Retrieves the current breadcrumb (or empty if not required).
	 *
	 * @return string the HTML breadcrumb
	 */
	public function getBreadcrumb(): string;

	/**
	 * Retrieves the HTML to print on the page
	 */
	public function showHtmlTemplate();

	/**
	 * Retrieves values to print in JSON format
	 *
	 * @return mixed values to show in JSON (string, array, whatever)
	 */
	public function getJsonTemplate();

}
