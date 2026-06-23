<?php

namespace Tests\Feature;

use App\Livewire\Admin\AdminPanel;
use App\Models\CountdownSetting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class CountdownTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_countdown_can_be_rendered_without_login(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Contador oficial')
            ->assertSee('Hackathon 2026')
            ->assertDontSee('Sincronização');
    }

    public function test_admin_route_renders_login_for_guests(): void
    {
        $this->get('/admin')
            ->assertOk()
            ->assertSee('Entrar no painel');
    }

    public function test_countdown_state_endpoint_returns_server_synced_payload(): void
    {
        $this->getJson('/api/countdown-state')
            ->assertOk()
            ->assertJsonPath('event_name', 'Hackathon 2026')
            ->assertJsonPath('timezone', 'America/Sao_Paulo')
            ->assertJsonStructure([
                'server_now',
                'start_at',
                'end_at',
                'sync_version',
                'last_synced_at',
            ]);
    }

    public function test_seeders_create_admin_and_default_countdown_settings(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'admin@contador.com')->firstOrFail();

        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('Contador1!', $admin->password));
        $this->assertDatabaseHas('countdown_settings', [
            'id' => 1,
            'event_name' => 'Hackathon 2026',
            'timezone' => 'America/Sao_Paulo',
        ]);
    }

    public function test_admin_can_update_countdown_settings(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        CountdownSetting::current();

        Livewire::actingAs($admin)
            ->test(AdminPanel::class)
            ->set('event_name', 'Hackathon 2026 Oficial')
            ->set('starts_date', '2026-07-04')
            ->set('starts_time', '10:00')
            ->set('ends_date', '2026-07-05')
            ->set('ends_time', '16:00')
            ->set('timezone', 'America/Sao_Paulo')
            ->set('before_start_text', 'Faltam para o início')
            ->set('running_text', 'Evento em andamento')
            ->set('finished_text', 'Evento encerrado')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('countdown_settings', [
            'id' => 1,
            'event_name' => 'Hackathon 2026 Oficial',
            'starts_at' => '2026-07-04 10:00:00',
            'ends_at' => '2026-07-05 16:00:00',
            'sync_version' => 2,
        ]);
    }
}
