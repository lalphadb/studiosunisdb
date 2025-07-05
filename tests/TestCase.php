<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Configuration pour tests SQLite
        if (config('database.default') === 'testing') {
            $this->artisan('migrate:fresh');
        }
    }
}
