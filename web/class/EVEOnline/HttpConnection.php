<?php

namespace ESI;

use Utils\Enum\BasicEnum;

/**
 * HttpConnection for ESI call.
 *
 * @package ESI
 */
class HttpConnection extends BasicEnum {

	const GET = "GET";
	const POST = "POST";
	const PUT = "PUT";
	const DELETE = "DELETE";

}