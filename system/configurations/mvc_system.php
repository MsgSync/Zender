<?php
/**
 * Framework System Config
 * @package MVC Framework
 * @author KhulnaSoft <mail@khulnasoft.com>
 */

date_default_timezone_set("UTC");
define("base_dir", "system/");
define("error_handler", 1);

/**
 * Project Configs
 */

define("site_url", "//" . env["siteurl"] . env["port"] . env["subdir"]);
define("titansys_api", "https://api.khulnasoft.com");
define("titansys_builder", "https://builder.khulnasoft.com");
define("system_token", env["systoken"]);
