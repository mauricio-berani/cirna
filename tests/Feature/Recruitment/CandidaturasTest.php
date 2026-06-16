<?php

namespace Tests\Feature\Recruitment;

use App\Enums\Auth\Roles;
use App\Livewire\Recruitment\Application\IndexComponent;
use App\Models\Auth\User;
use App\Models\Recruitment\Application;
use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CandidaturasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionRoleSeeder::class);
    }

    private function administrator(): User
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::ADMINISTRATOR->value);

        return $user;
    }

    private function application(): Application
    {
        $application = new Application;
        $application->forceFill([
            Application::FIELD_NAME => 'Maria Candidata',
            Application::FIELD_EMAIL => 'maria@email.com',
            Application::FIELD_PHONE => '(54) 99999-9999',
            Application::FIELD_AREA => 'quality',
            Application::FIELD_RESUME_PATH => 'applications/maria-cv.pdf',
        ])->save();

        return $application;
    }

    public function test_an_administrator_can_access_the_applications_index(): void
    {
        $this->actingAs($this->administrator())
            ->get(route('candidaturas.index'))
            ->assertOk();
    }

    public function test_a_regular_user_cannot_access_the_applications_index(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::USER->value);

        $this->actingAs($user)
            ->get(route('candidaturas.index'))
            ->assertForbidden();
    }

    public function test_an_administrator_can_view_an_application(): void
    {
        $application = $this->application();

        $this->actingAs($this->administrator())
            ->get(route('candidaturas.show', ['itemId' => $application->id]))
            ->assertOk()
            ->assertSee('Maria Candidata');
    }

    public function test_the_download_action_redirects_to_a_signed_url(): void
    {
        $application = $this->application();

        Livewire::actingAs($this->administrator())
            ->test(IndexComponent::class)
            ->call('download', $application->id)
            ->assertRedirect();
    }

    public function test_the_area_filter_narrows_the_listing(): void
    {
        $this->application(); // quality

        $other = new Application;
        $other->forceFill([
            Application::FIELD_NAME => 'Pedro Produção',
            Application::FIELD_EMAIL => 'pedro@email.com',
            Application::FIELD_AREA => 'production',
            Application::FIELD_RESUME_PATH => 'applications/pedro-cv.pdf',
        ])->save();

        Livewire::actingAs($this->administrator())
            ->test(IndexComponent::class)
            ->set('area', 'production')
            ->assertSee('Pedro Produção')
            ->assertDontSee('Maria Candidata');
    }
}
