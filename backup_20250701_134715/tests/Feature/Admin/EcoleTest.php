protected function setUp(): void
{
    parent::setUp();
    
    // Créer le rôle membre pour les tests SQLite
    \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'membre']);
    
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
}
