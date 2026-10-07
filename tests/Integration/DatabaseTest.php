<?php

/**
 * Integration tests against the seeded test database (tests/Integration).
 *
 * Skips automatically when pdo_mysql or the test database is unavailable;
 * run inside Docker (make test) or CI for full coverage.
 */

class DatabaseTest extends ZenderTestCase
{
    public function testConnectionExecutesSimpleQuery()
    {
        $row = $this->db()->query_one("SELECT 1 AS ok");

        $this->assertIsArray($row);
        $this->assertEquals(1, $row["ok"]);
    }

    public function testSchemaIsSeeded()
    {
        $row = $this->db()->query_one(
            "SELECT COUNT(*) AS tables_found FROM information_schema.tables WHERE table_schema = DATABASE()"
        );

        $this->assertGreaterThanOrEqual(21, (int) $row["tables_found"]);
    }

    public function testSettingsTableContainsRows()
    {
        $row = $this->db()->query_one("SELECT COUNT(*) AS c FROM settings");

        $this->assertGreaterThan(0, (int) $row["c"]);
    }

    public function testModelInstancesResolveDatabaseHandle()
    {
        $this->db(); // skip cleanly when the test database is unavailable

        $model = new System_Model();

        $this->assertInstanceOf(MVC_PDO::class, $model->db);

        $row = $model->db->query_one("SELECT COUNT(*) AS c FROM settings");
        $this->assertGreaterThan(0, (int) $row["c"]);
    }
}
