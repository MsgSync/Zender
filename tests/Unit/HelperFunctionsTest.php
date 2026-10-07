<?php

/**
 * Unit tests for the global helper functions in system/plugins/mvc_functions.php
 */

class HelperFunctionsTest extends ZenderTestCase
{
    public function testTruncateCapsArrayAtMaxKeepingNewestKeys()
    {
        $items = ["a" => 1, "b" => 2, "c" => 3, "d" => 4];

        $this->assertSame(["d" => 4, "c" => 3], truncate($items, 2));
        $this->assertSame(["d" => 4, "c" => 3, "b" => 2, "a" => 1], truncate($items, 10));
        $this->assertSame([], truncate([], 5));
    }

    public function testCountMonths()
    {
        $this->assertSame(1, count_months("2024-01-01", "2024-01-31"));
        $this->assertSame(2, count_months("2024-01-15", "2024-02-15"));
        $this->assertSame(2, count_months("2024-11-15", "2024-12-25"));

        // Argument order must not matter
        $this->assertSame(
            count_months("2024-01-15", "2024-03-20"),
            count_months("2024-03-20", "2024-01-15")
        );
        $this->assertSame(3, count_months("2024-01-15", "2024-03-20"));
    }

    public function testLimitationReturnsTrueWhenUsageReachedLimit()
    {
        $this->assertFalse(limitation(10, 9));
        $this->assertTrue(limitation(10, 10));
        $this->assertTrue(limitation(10, 11));
        $this->assertTrue(limitation(0, 0));
    }

    public function testVersionConstantMatchesVersionFile()
    {
        $this->assertTrue(defined("version"));
        $this->assertSame(file_get_contents("system/configurations/cc_ver.inc"), version);
    }
}
