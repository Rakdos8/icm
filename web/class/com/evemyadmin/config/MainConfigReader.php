<?php

namespace com\evemyadmin\config;

use net\bourelly\core\config\ConfigReader;
use net\bourelly\core\utils\handler\ErrorHandler;

/**
 * Class ConfigReader.<br>
 * Reads the ini config file and set defines accordingly.
 *
 * @package com\evemyadmin\config
 */
class MainConfigReader extends ConfigReader {

	protected $pathIniFile;

	public function __construct(
			string $pathIniFile
	) {
		parent::__construct($pathIniFile);
	}

	protected function parse(array $iniConfig): bool {
		try {
			define("DEBUG", boolval($iniConfig['show_errors']), false);

			// Domain and application config
			define("DOMAIN", $iniConfig['domain'], false);
			define("FULL_DOMAIN", "https://" . DOMAIN, false);
			define("APPLICATION_NAME", $iniConfig['application_name'], false);
			define("PATH_APPLICATION_CLASS", $iniConfig['root_namespace_application'], false);

			// Cookie configuration
			define("DISCLAIMER_NAME", $iniConfig['disclaimer_name'], false);
			define("COOKIE_DEFAULT_EXPIRATION", $iniConfig['default_expiration'], false);
			// Session architecture
			define("SESSION_CURRENT_URI", "current_uri", false);
			define("SESSION_EVE_CHARACTERS", "characters", false);
			define("SESSION_MAIN_CHARACTER", "main_character", false);

			// Mail addresses in case of error
			define("MAIL_DEVELOPER", $iniConfig['developer'], false);
			define("MAIL_ADMINISTRATOR", $iniConfig['admin'], false);

			// Database login/password
			define("DB_LOGIN", $iniConfig['login'], false);
			define("DB_PASSWORD", $iniConfig['password'], false);
			define("DB_URL", $iniConfig['url'], false);
			define("DB_PORT", $iniConfig['port'], false);
			define("DB_NAME", "`" . $iniConfig['schema_name'] . "`", false);

			// Define of RE-CAPTCHA v2 from Google
			define("RE-CAPTCHA_PUBLIC", $iniConfig['recaptcha_public'], false);
			define("RE-CAPTCHA_PRIVATE", $iniConfig['recaptcha_private'], false);

			// Define of EVE Online EsiFactory system
			define("ESI_BASE_URL", $iniConfig['esi_base_url'], false);
			define("ESI_LOGIN_BASE_URL", "https://login.eveonline.com", false);
			define("ESI_CLIENT_ID", $iniConfig['client_id'], false);
			define("ESI_SECRET_KEY", $iniConfig['secret_key'], false);
			define("ESI_CALLBACK_URL", $iniConfig['callback_url'], false);
			define("ESI_SCOPE", $iniConfig['esi_scope'], false);
			define(
					"OAUTH_LOGIN_URL",
					ESI_LOGIN_BASE_URL .
					"/oauth/authorize?response_type=code&redirect_uri=" .
					urlencode(FULL_DOMAIN . "/" . ESI_CALLBACK_URL) .
					"&client_id=" .
					ESI_CLIENT_ID .
					"&scope=" .
					ESI_SCOPE,
					false
			);

			define("PATH_ESI_CACHE", PATH_PROJECT . "/esi-cache/", false);
			define("PATH_ESI_LOG", PATH_ROOT . "/log/", false);

			// Define of EVE Online utility stuff
			define("IMAGE_SERVER_URL", $iniConfig['image_server_url'], false);

			// PhpBB config part
			define("PHPBB_URL", $iniConfig['phpbb_url'], false);
			define("PATH_PHPBB", $iniConfig['phpbb_path'], false);

			// Some define for link between ESI and phpBB
			define("CORPORATION_ID", $iniConfig['corporation_id'], false);
			define("PHPBB_GROUP_VERIFIED_ID", $iniConfig['phpbb_group_verified_id'], false);
			define("PHPBB_GROUP_DIRECTOR_ID", $iniConfig['phpbb_group_director_id'], false);
			define("PHPBB_GROUP_FRIEND_ID", $iniConfig['phpbb_group_friend_id'], false);

			// Some define for REDIS link with ESI
			define("USE_REDIS", boolval($iniConfig['use_redis']), false);
			define("REDIS_URL", $iniConfig['redis_url'], false);
			define("REDIS_DATABASE", $iniConfig['redis_database'], false);
			define("REDIS_PASSWORD", $iniConfig['redis_password'], false);
			define("REDIS_CACHE_PREFIX", $iniConfig['redis_cache_prefix'], false);

			return true;
		} catch (\Throwable $ex) {
			ErrorHandler::logException($ex);
		}
		return false;
	}

}
