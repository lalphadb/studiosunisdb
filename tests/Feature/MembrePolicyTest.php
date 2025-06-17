<?php

namespace Tests\Feature;

use App\Models\Ecole;
use App\Models\Membre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembrePolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_view_all_membres()
    {
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $ecole = Ecole::factory()->create();
        $membre = Membre::factory()->create(['ecole_id' => $ecole->id]);

        $this->assertTrue($superadmin->can('view', $membre));
    }

    public function test_admin_can_only_view_own_school_membres()
    {
        $ecole1 = Ecole::factory()->create();
        $ecole2 = Ecole::factory()->create();

        $admin = User::factory()->create(['ecole_id' => $ecole1->id]);
        $admin->assignRole('admin');

        $membreEcole1 = Membre::factory()->create(['ecole_id' => $ecole1->id]);
        $membreEcole2 = Membre::factory()->create(['ecole_id' => $ecole2->id]);

        $this->assertTrue($admin->can('view', $membreEcole1));
        $this->assertFalse($admin->can('view', $membreEcole2));
    }
}
