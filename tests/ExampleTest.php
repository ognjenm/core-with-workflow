<?php

namespace Telenok\Core;

class ExampleTest extends \Illuminate\Foundation\Testing\TestCase {

    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__ . '/../../../../bootstrap/start.php';
    }

    public function testBasicExample()
    {
        $this->assertTrue(true);
    }

}