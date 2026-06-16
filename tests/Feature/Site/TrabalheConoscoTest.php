<?php

namespace Tests\Feature\Site;

use App\Livewire\Site\TrabalheConoscoComponent;
use App\Mail\ApplicationReceivedMail;
use App\Models\Recruitment\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class TrabalheConoscoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        Storage::fake('private');
        RateLimiter::clear('application-form:127.0.0.1');
    }

    private function pdf(string $name = 'curriculo.pdf'): UploadedFile
    {
        return UploadedFile::fake()->createWithContent(
            $name,
            "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF"
        );
    }

    public function test_the_careers_page_is_accessible_to_guests(): void
    {
        $this->get(route('site.trabalhe-conosco'))->assertOk();
    }

    public function test_a_valid_application_is_stored_and_emailed(): void
    {
        Mail::fake();

        Livewire::test(TrabalheConoscoComponent::class)
            ->set('form.name', 'João Candidato')
            ->set('form.email', 'joao@email.com')
            ->set('form.phone', '(54) 99999-9999')
            ->set('form.area', 'production')
            ->set('form.resume', $this->pdf())
            ->call('send')
            ->assertHasNoErrors();

        $application = Application::query()->first();

        $this->assertNotNull($application);
        $this->assertSame('João Candidato', $application->name);
        $this->assertStringStartsWith('applications/', $application->resume_path);
        Storage::disk('private')->assertExists($application->resume_path);

        Mail::assertSent(ApplicationReceivedMail::class);
    }

    public function test_the_honeypot_blocks_spam(): void
    {
        Mail::fake();

        Livewire::test(TrabalheConoscoComponent::class)
            ->set('form.name', 'Spam Bot')
            ->set('form.email', 'bot@spam.com')
            ->set('form.area', 'other')
            ->set('form.resume', $this->pdf())
            ->set('form.website', 'http://spam.example')
            ->call('send')
            ->assertHasErrors('form.website');

        $this->assertSame(0, Application::query()->count());
        Mail::assertNothingSent();
    }

    public function test_non_pdf_uploads_are_rejected(): void
    {
        Mail::fake();

        Livewire::test(TrabalheConoscoComponent::class)
            ->set('form.name', 'João Candidato')
            ->set('form.email', 'joao@email.com')
            ->set('form.area', 'production')
            ->set('form.resume', UploadedFile::fake()->create('malware.exe', 10))
            ->call('send')
            ->assertHasErrors('form.resume');

        $this->assertSame(0, Application::query()->count());
        Mail::assertNothingSent();
    }

    public function test_a_renamed_non_pdf_is_rejected_by_signature_check(): void
    {
        Mail::fake();

        Livewire::test(TrabalheConoscoComponent::class)
            ->set('form.name', 'João Candidato')
            ->set('form.email', 'joao@email.com')
            ->set('form.area', 'production')
            ->set('form.resume', UploadedFile::fake()->createWithContent('fake.pdf', 'GIF89a not a pdf'))
            ->call('send')
            ->assertHasErrors('form.resume');

        $this->assertSame(0, Application::query()->count());
        Mail::assertNothingSent();
    }
}
