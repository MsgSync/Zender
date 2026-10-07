<?php

/**
 * PHPUnit bootstrap.
 *
 * Loads the Zender framework the same way index.php does, but without
 * dispatching a request (MVC_NO_DISPATCH is honoured by system/framework.php).
 *
 * The test environment lives in tests/config/cc_env.inc and is generated
 * from the sample below on first run, so tests never touch the credentials
 * in system/configurations/cc_env.inc.
 */

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

$root = dirname(__DIR__);
chdir($root);

/*
 * CLI SAPI: pin the server globals get_configs() and the router read.
 * Force-set (not +=) because the CLI provides its own SCRIPT_NAME etc.
 */
$_SERVER["SERVER_NAME"] = "localhost";
$_SERVER["SERVER_PORT"] = "80";
$_SERVER["HTTP_HOST"] = "localhost";
$_SERVER["SCRIPT_NAME"] = "/index.php";
$_SERVER["REQUEST_URI"] = "/";
$_SERVER["REQUEST_METHOD"] = "GET";
$_SERVER["REMOTE_ADDR"] = "127.0.0.1";

/*
 * Test database config. Regenerated whenever a ZENDER_TEST_DB_* override is
 * present so containers can inject their own values on every run.
 */
$testEnvFile = __DIR__ . "/config/cc_env.inc";
$overrides = [
    "dbhost" => getenv("ZENDER_TEST_DB_HOST"),
    "dbport" => getenv("ZENDER_TEST_DB_PORT"),
    "dbname" => getenv("ZENDER_TEST_DB_NAME"),
    "dbuser" => getenv("ZENDER_TEST_DB_USER"),
    "dbpass" => getenv("ZENDER_TEST_DB_PASS"),
];
$hasOverrides = array_filter($overrides, static fn ($v) => $v !== false && $v !== "");

if (!file_exists($testEnvFile) || $hasOverrides) {
    if (!is_dir(dirname($testEnvFile))) {
        mkdir(dirname($testEnvFile), 0775, true);
    }

    $values = [
        "dbhost" => $overrides["dbhost"] !== false ? $overrides["dbhost"] : "127.0.0.1",
        "dbport" => $overrides["dbport"] !== false ? $overrides["dbport"] : "3306",
        "dbname" => $overrides["dbname"] !== false ? $overrides["dbname"] : "zender_test",
        "dbuser" => $overrides["dbuser"] !== false ? $overrides["dbuser"] : "root",
        "dbpass" => $overrides["dbpass"] !== false ? $overrides["dbpass"] : "root",
        "systoken" => "test-token",
        "installed" => "1",
    ];

    $contents = "";
    foreach ($values as $key => $value) {
        $contents .= "{$key}<=>{$value}\n";
    }

    file_put_contents($testEnvFile, $contents);
}

putenv("ZENDER_CC_ENV={$testEnvFile}");
$_ENV["ZENDER_CC_ENV"] = $testEnvFile;

require $root . "/vendor/autoload.php";

define("MVC_NO_DISPATCH", true);
require "system/framework.php";
