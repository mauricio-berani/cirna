<?php

namespace Tests\Feature\Common;

use App\Enums\Auth\Roles;
use App\Livewire\Common\Settings\UpdateComponent;
use App\Models\Auth\User;
use App\Models\Common\Setting;
use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('private');
        $this->seed(PermissionRoleSeeder::class);
    }

    private function administrator(): User
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::ADMINISTRATOR->value);

        return $user;
    }

    private function pdf(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            'certificado.pdf',
            "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF"
        );
    }

    public function test_an_administrator_can_open_the_settings_page(): void
    {
        $this->actingAs($this->administrator())
            ->get(route('settings'))
            ->assertOk();
    }

    public function test_a_regular_user_cannot_open_the_settings_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::USER->value);

        $this->actingAs($user)
            ->get(route('settings'))
            ->assertForbidden();
    }

    public function test_an_administrator_can_update_the_careers_email(): void
    {
        Livewire::actingAs($this->administrator())
            ->test(UpdateComponent::class)
            ->set('form.careers_email', 'rh@cirna.com.br')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('settings', [
            'key' => Setting::KEY_CAREERS_EMAIL,
            'value' => 'rh@cirna.com.br',
        ]);

        $this->assertSame('rh@cirna.com.br', Setting::careersEmail());
    }

    public function test_an_invalid_email_is_rejected(): void
    {
        Livewire::actingAs($this->administrator())
            ->test(UpdateComponent::class)
            ->set('form.careers_email', 'not-an-email')
            ->call('update')
            ->assertHasErrors('form.careers_email');
    }

    public function test_it_falls_back_to_config_when_unset(): void
    {
        config(['client.contact_email' => 'fallback@cirna.com.br']);

        $this->assertSame('fallback@cirna.com.br', Setting::careersEmail());
    }

    public function test_an_administrator_can_upload_the_iso_certificate(): void
    {
        Livewire::actingAs($this->administrator())
            ->test(UpdateComponent::class)
            ->set('form.certificate', $this->pdf())
            ->call('update')
            ->assertHasNoErrors();

        $path = Setting::isoCertificatePath();

        $this->assertNotNull($path);
        $this->assertStringStartsWith('certificates/', $path);
        Storage::disk('private')->assertExists($path);
    }

    public function test_a_non_pdf_certificate_is_rejected(): void
    {
        Livewire::actingAs($this->administrator())
            ->test(UpdateComponent::class)
            ->set('form.certificate', UploadedFile::fake()->create('virus.exe', 10))
            ->call('update')
            ->assertHasErrors('form.certificate');

        $this->assertNull(Setting::isoCertificatePath());
    }

    public function test_the_public_certificate_route_returns_404_when_unset(): void
    {
        $this->get(route('site.certificate'))->assertNotFound();
    }

    public function test_the_public_certificate_route_serves_the_pdf_when_set(): void
    {
        Livewire::actingAs($this->administrator())
            ->test(UpdateComponent::class)
            ->set('form.certificate', $this->pdf())
            ->call('update')
            ->assertHasNoErrors();

        $this->get(route('site.certificate'))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }
}
