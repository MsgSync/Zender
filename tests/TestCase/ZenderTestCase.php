<?php

/**
 * Base test case.
 *
 * Boots the MVC object graph (MVC -> controller -> MVC_Load -> MVC_PDO) the
 * same way MVC::run() does, and provides database availability checks so
 * integration tests skip cleanly where pdo_mysql or the test DB is missing.
 */

use PHPUnit\Framework\TestCase;

abstract class ZenderTestCase extends TestCase
{
    /** @var bool */
    private static $mvcBooted = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootMvc();
    }

    /**
     * Register the default MVC instance and a bare controller with a loader,
     * mirroring MVC::run() / MVC::controllers() without routing a request.
     */
    protected function bootMvc(): void
    {
        if (self::$mvcBooted) {
            return;
        }

        // Registers the default instance + controller the same way MVC::run() does.
        $mvc = new MVC();
        $mvc->controller = new MVC_Controller();
        self::$mvcBooted = true;
    }

    /**
     * Database handle from the test pool, or skip if unavailable/unseeded.
     *
     * @return MVC_PDO
     */
    protected function db()
    {
        if (!extension_loaded("pdo_mysql")) {
            $this->markTestSkipped("pdo_mysql is not available; run tests inside Docker or CI");
        }

        $config = database["default"];
        $dsn = "mysql:host={$config["host"]};port={$config["port"]};dbname={$config["name"]}";

        try {
            $pdo = new PDO($dsn, $config["user"], $config["pass"], [
                PDO::ATTR_TIMEOUT => 3,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $seeded = (bool) $pdo->query("SELECT 1 FROM settings LIMIT 1")->fetchColumn();
            $pdo = null;
        } catch (Throwable $e) {
            $this->markTestSkipped(
                "Test database not reachable or not seeded ({$e->getMessage()}); run tools/seed-db.sh"
            );
        }

        if (!$seeded) {
            $this->markTestSkipped("Test database is empty; run tools/seed-db.sh");
        }

        return mvc::instance(false, "controller")->load->database();
    }
}
