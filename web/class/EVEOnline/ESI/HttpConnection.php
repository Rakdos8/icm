<?php

namespace EVEOnline\ESI;

use Utils\Enum\BasicEnum;

/**
 * HttpConnection for ESI call.
 *
 * @package EVEOnline\ESI
 */
class HttpConnection extends BasicEnum {

	const GET = "GET";
	const POST = "POST";
	const PUT = "PUT";
	const DELETE = "DELETE";

}