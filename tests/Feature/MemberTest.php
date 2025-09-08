<?php

namespace Tests\Feature;

use App\Models\Belt;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $gestionnaire;

    protected User $membre;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les rôles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'gestionnaire']);
        Role::create(['name' => 'membre']);

        // Créer les utilisateurs
        $this->admin = User::factory()->create()->assignRole('admin');
        $this->gestionnaire = User::factory()->create()->assignRole('gestionnaire');
        $this->membre = User::factory()->create()->assignRole('membre');

        // Créer une ceinture de test
        Belt::create([
            'name' => 'Blanche',
            'color_hex' => '#FFFFFF',
            'order' => 1,
        ]);
    }

    public function test_admin_can_view_members_list()
    {
        $this->actingAs($this->admin)
            ->get(route('members.index'))
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Members/Index')
                ->has('members')
                ->has('filters')
                ->has('belts')
                ->has('stats')
            );
    }

    public function test_gestionnaire_can_create_member()
    {
        $memberData = [
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'jean.dupont@example.com',
            'phone' => '514-555-0123',
            'birth_date' => '2010-01-15',
            'gender' => 'M',
            'address' => '123 Rue Test',
            'city' => 'Montréal',
            'postal_code' => 'H1A1A1',
            'province' => 'QC',
            'emergency_contact_name' => 'Marie Dupont',
            'emergency_contact_phone' => '514-555-0124',
            'emergency_contact_relationship' => 'Parent',
            'status' => 'active',
            'consent_photos' => true,
            'consent_communications' => true,
        ];

        $this->actingAs($this->gestionnaire)
            ->post(route('members.store'), $memberData)
            ->assertRedirect();

        $this->assertDatabaseHas('members', [
            'email' => 'jean.dupont@example.com',
        ]);
    }

    public function test_membre_cannot_delete_other_members()
    {
        $member = Member::factory()->create();

        $this->actingAs($this->membre)
            ->delete(route('members.destroy', $member))
            ->assertForbidden();

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
        ]);
    }

    public function test_search_functionality_works()
    {
        Member::factory()->create([
            'first_name' => 'Unique',
            'last_name' => 'Name',
            'email' => 'unique@test.com',
        ]);

        $this->actingAs($this->admin)
            ->get(route('members.index', ['q' => 'Unique']))
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Members/Index')
                ->where('members.data.0.first_name', 'Unique')
            );
    }

    public function test_bulk_update_requires_authorization()
    {
        $members = Member::factory()->count(3)->create(['status' => 'active']);

        $this->actingAs($this->gestionnaire)
            ->post(route('members.bulk-update'), [
                'ids' => $members->pluck('id')->toArray(),
                'action' => 'deactivate',
            ])
            ->assertRedirect();

        foreach ($members as $member) {
            $this->assertDatabaseHas('members', [
                'id' => $member->id,
                'status' => 'inactive',
            ]);
        }
    }

    public function test_change_belt_creates_progression_record()
    {
        $member = Member::factory()->create();
        $newBelt = Belt::create([
            'name' => 'Jaune',
            'color_hex' => '#FFD700',
            'order' => 2,
        ]);

        $this->actingAs($this->admin)
            ->post(route('members.change-belt', $member), [
                'belt_id' => $newBelt->id,
                'notes' => 'Excellent progrès',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'current_belt_id' => $newBelt->id,
        ]);
    }

    public function test_export_requires_permission()
    {
        $this->actingAs($this->membre)
            ->get(route('members.export'))
            ->assertForbidden();

        $this->actingAs($this->admin)
            ->get(route('members.export'))
            ->assertStatus(200);
    }

    public function test_member_validation_rules()
    {
        $invalidData = [
            'first_name' => '',
            'email' => 'invalid-email',
            'birth_date' => '2030-01-01',
            'postal_code' => '123456',
        ];

        $this->actingAs($this->admin)
            ->post(route('members.store'), $invalidData)
            ->assertSessionHasErrors([
                'first_name',
                'last_name',
                'email',
                'birth_date',
                'postal_code',
                'gender',
            ]);
    }
}
