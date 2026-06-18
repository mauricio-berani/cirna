<?php

namespace Tests\Feature\Site;

use App\Livewire\Site\ContatoComponent;
use App\Mail\ContactMessageMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function publicRoutes(): array
    {
        return [
            'home' => ['site.home'],
            'empresa' => ['site.empresa'],
            'historico' => ['site.historico'],
            'qualidade' => ['site.qualidade'],
            'servicos' => ['site.servicos'],
            'clientes' => ['site.clientes'],
            'contato' => ['site.contato'],
        ];
    }

    #[DataProvider('publicRoutes')]
    public function test_public_pages_are_accessible_to_guests(string $routeName): void
    {
        $this->get(route($routeName))->assertOk();
    }

    public function test_the_harassment_channel_card_is_hidden_when_url_is_unset(): void
    {
        config(['client.harassment_channel_url' => '']);

        $this->get(route('site.contato'))
            ->assertOk()
            ->assertDontSee('Canal de Assédio');
    }

    public function test_the_harassment_channel_card_links_in_a_new_tab_when_set(): void
    {
        config(['client.harassment_channel_url' => 'https://exemplo.com/canal-assedio']);

        $this->get(route('site.contato'))
            ->assertOk()
            ->assertSee('Canal de Assédio')
            ->assertSee('https://exemplo.com/canal-assedio')
            ->assertSee('target="_blank"', false);
    }

    public function test_contact_form_sends_an_email_on_valid_submission(): void
    {
        Mail::fake();
        RateLimiter::clear('contact-form:127.0.0.1');

        Livewire::test(ContatoComponent::class)
            ->set('form.name', 'Maria Silva')
            ->set('form.email', 'maria@empresa.com')
            ->set('form.phone', '(54) 99999-9999')
            ->set('form.sector', 'sales')
            ->set('form.message', 'Gostaria de um orçamento para moldes.')
            ->call('send')
            ->assertHasNoErrors();

        Mail::assertSent(ContactMessageMail::class);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        Mail::fake();

        Livewire::test(ContatoComponent::class)
            ->set('form.name', '')
            ->set('form.email', 'not-an-email')
            ->set('form.message', '')
            ->call('send')
            ->assertHasErrors(['form.name', 'form.email', 'form.message']);

        Mail::assertNothingSent();
    }

    public function test_contact_form_honeypot_blocks_spam(): void
    {
        Mail::fake();

        Livewire::test(ContatoComponent::class)
            ->set('form.name', 'Spam Bot')
            ->set('form.email', 'bot@spam.com')
            ->set('form.message', 'Mensagem automática de spam.')
            ->set('form.website', 'http://spam.example')
            ->call('send')
            ->assertHasErrors('form.website');

        Mail::assertNothingSent();
    }
}
