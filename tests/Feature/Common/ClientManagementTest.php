<?php

namespace Tests\Feature\Common;

use App\Enums\Auth\Roles;
use App\Livewire\Common\Client\FormComponent;
use App\Livewire\Common\Client\IndexComponent;
use App\Models\Common\Client;
use App\Models\Common\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('public');
    }

    private function logo(): UploadedFile
    {
        return UploadedFile::fake()->image('logo.png', 200, 80);
    }

    private function makeClient(array $overrides = []): Client
    {
        $client = new Client;

        $client->forceFill(array_merge([
            Client::FIELD_NAME => 'Marcopolo',
            Client::FIELD_LOGO => 'assets/cirna/clientes/marcopolo.png',
            Client::FIELD_URL => 'https://www.marcopolo.com.br/',
        ], $overrides))->save();

        return $client;
    }

    public function test_an_administrator_can_open_the_clients_index(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get(route('clientes.index'))
            ->assertOk();
    }

    public function test_a_regular_user_cannot_open_the_clients_index(): void
    {
        $this->actingAs($this->makeUser(Roles::USER))
            ->get(route('clientes.index'))
            ->assertForbidden();
    }

    public function test_an_administrator_can_create_a_client(): void
    {
        Livewire::actingAs($this->makeAdmin())
            ->test(FormComponent::class)
            ->set('form.name', 'Agrale')
            ->set('form.url', 'https://www.agrale.com.br/')
            ->set('form.logo', $this->logo())
            ->call('save')
            ->assertHasNoErrors();

        $client = Client::query()->where(Client::FIELD_NAME, 'Agrale')->first();

        $this->assertNotNull($client);
        $this->assertStringStartsWith('clientes/', $client->logo);
        Storage::disk('public')->assertExists($client->logo);
    }

    public function test_creating_a_client_requires_a_logo(): void
    {
        Livewire::actingAs($this->makeAdmin())
            ->test(FormComponent::class)
            ->set('form.name', 'Sem Logo')
            ->call('save')
            ->assertHasErrors('form.logo');

        $this->assertDatabaseMissing(Client::TABLE, [Client::FIELD_NAME => 'Sem Logo']);
    }

    public function test_an_invalid_url_is_rejected(): void
    {
        Livewire::actingAs($this->makeAdmin())
            ->test(FormComponent::class)
            ->set('form.name', 'URL Inválida')
            ->set('form.url', 'not-a-url')
            ->set('form.logo', $this->logo())
            ->call('save')
            ->assertHasErrors('form.url');
    }

    public function test_a_regular_user_cannot_open_the_create_form(): void
    {
        $this->actingAs($this->makeUser(Roles::USER))
            ->get(route('clientes.create'))
            ->assertForbidden();
    }

    public function test_an_administrator_can_update_a_client_keeping_the_logo(): void
    {
        $client = $this->makeClient();

        Livewire::actingAs($this->makeAdmin())
            ->test(FormComponent::class, ['itemId' => $client->getKey()])
            ->set('form.name', 'Marcopolo S.A.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas(Client::TABLE, [
            Client::FIELD_ID => $client->getKey(),
            Client::FIELD_NAME => 'Marcopolo S.A.',
            Client::FIELD_LOGO => 'assets/cirna/clientes/marcopolo.png',
        ]);
    }

    public function test_an_administrator_can_delete_a_client(): void
    {
        $client = $this->makeClient();

        Livewire::actingAs($this->makeAdmin())
            ->test(IndexComponent::class)
            ->call('confirmAction', $client->name, $client->getKey())
            ->call('delete')
            ->assertHasNoErrors();

        $this->assertSoftDeleted(Client::TABLE, [Client::FIELD_ID => $client->getKey()]);
    }

    public function test_the_home_hides_the_clients_section_by_default(): void
    {
        $this->makeClient();

        $this->get(route('site.home'))
            ->assertOk()
            ->assertDontSee('Empresas que confiam na Cirna');
    }

    public function test_the_home_shows_clients_when_the_flag_is_enabled(): void
    {
        $this->makeClient();
        Setting::put(Setting::KEY_SHOW_CLIENTS, '1');

        $this->get(route('site.home'))
            ->assertOk()
            ->assertSee('Empresas que confiam na Cirna');
    }
}
