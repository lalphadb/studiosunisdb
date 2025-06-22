<?php

namespace Tests\Feature;

use Tests\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
    }

    protected function seedRolesAndPermissions()
    {
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'instructeur']);
        Role::firstOrCreate(['name' => 'membre']);
    }
}
