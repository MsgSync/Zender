<?php

/**
 * Unit tests for the framework configuration loaded by the bootstrap.
 */

class FrameworkConfigTest extends ZenderTestCase
{
    public function testFrameworkClassesAreLoadable()
    {
        $this->assertTrue(class_exists("MVC"));
        $this->assertTrue(class_exists("MVC_Controller"));
        $this->assertTrue(class_exists("MVC_Load"));
        $this->assertTrue(class_exists("MVC_Model"));
        $this->assertTrue(class_exists("MVC_PDO"));
    }

    public function testApplicationConfiguration()
    {
        $this->assertFalse(configuration["root_controller"]);
        $this->assertFalse(configuration["root_action"]);
        $this->assertSame("default", configuration["default_controller"]);
        $this->assertSame("index", configuration["default_action"]);
    }

    public function testAutoloadListsLibrariesAndModels()
    {
        $this->assertNotEmpty(autoload["libraries"]);
        $this->assertNotEmpty(autoload["models"]);

        $libraries = array_map(static fn ($lib) => is_array($lib) ? $lib[0] : $lib, autoload["libraries"]);
        $models = array_map(static fn ($model) => is_array($model) ? $model[0] : $model, autoload["models"]);

        $this->assertContains("Smarty", $libraries);
        $this->assertContains("System_Model", $models);
    }

    public function testDatabasePoolConfiguration()
    {
        $this->assertArrayHasKey("default", database);
        $this->assertSame("MVC_PDO", database["default"]["plugin"]);
        $this->assertSame("mysql", database["default"]["type"]);
    }

    public function testEnvironmentUsesTestConfig()
    {
        $this->assertSame("1", env["installed"]);
        $this->assertNotEmpty(env["dbhost"]);
        $this->assertNotEmpty(env["dbname"]);
    }
}
