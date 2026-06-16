<?php

namespace Tests\Feature\Auth;

use App\Enums\Auth\Roles;
use App\Livewire\Auth\Profile\TwoFactorSetup;
use App\Livewire\Auth\Profile\UpdateComponent;
use App\Livewire\Auth\TwoFactorChallenge;
use App\Models\Auth\User;
use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Tests\TestCase;

class ProfileSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionRoleSeeder::class);
    }

    public function test_a_profile_update_rejects_non_image_uploads(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::USER->value);

        $this->actingAs($user);

        Livewire::test(UpdateComponent::class)
            ->set('form.name', 'Usuario Seguro')
            ->set('form.avatar', UploadedFile::fake()->create('payload.pdf', 10, 'application/pdf'))
            ->call('update')
            ->assertHasErrors(['form.avatar']);
    }

    public function test_a_profile_update_rejects_weak_passwords(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::USER->value);

        $this->actingAs($user);

        Livewire::test(UpdateComponent::class)
            ->set('form.name', 'Usuario Seguro')
            ->set('form.password', '123456')
            ->set('form.password_confirmation', '123456')
            ->call('update')
            ->assertHasErrors(['form.password']);
    }

    public function test_two_factor_disable_requires_a_valid_current_code(): void
    {
        $secret = Google2FA::generateSecretKey();
        $invalidCode = $this->invalidTwoFactorCode($secret);

        $user = User::factory()->create();
        $user->assignRole(Roles::USER->value);
        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->actingAs($user);

        Livewire::test(TwoFactorSetup::class)
            ->set('disableCode', $invalidCode)
            ->call('disable');

        $this->assertNotNull($user->fresh()->two_factor_secret);

        Livewire::test(TwoFactorSetup::class)
            ->set('disableCode', (string) Google2FA::getCurrentOtp($secret))
            ->call('disable');

        $this->assertNull($user->fresh()->two_factor_secret);
    }

    public function test_two_factor_challenge_rate_limits_invalid_codes(): void
    {
        $secret = Google2FA::generateSecretKey();
        $invalidCode = $this->invalidTwoFactorCode($secret);

        $user = User::factory()->create();
        $user->assignRole(Roles::USER->value);
        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->actingAs($user);

        $component = Livewire::test(TwoFactorChallenge::class);

        foreach (range(1, 5) as $attempt) {
            $component
                ->set('code', $invalidCode)
                ->call('verify');
        }

        $component
            ->set('code', (string) Google2FA::getCurrentOtp($secret))
            ->call('verify');

        $this->assertFalse(session()->has('two_factor_verified'));
    }

    public function test_private_avatar_files_are_available_to_the_owner(): void
    {
        [$path, $url] = $this->fakePrivateAvatar();

        $user = User::factory()->create([
            User::FIELD_AVATAR => $path,
        ]);
        $user->assignRole(Roles::USER->value);

        $this->actingAs($user)
            ->get($url)
            ->assertOk();
    }

    public function test_private_avatar_files_reject_users_without_access(): void
    {
        [$path, $url] = $this->fakePrivateAvatar();

        User::factory()->create([
            User::FIELD_AVATAR => $path,
        ])->assignRole(Roles::USER->value);

        $user = User::factory()->create();
        $user->assignRole(Roles::USER->value);

        $this->actingAs($user)
            ->get($url)
            ->assertNotFound();
    }

    public function test_private_avatar_files_are_available_to_users_with_read_permission(): void
    {
        [$path, $url] = $this->fakePrivateAvatar();

        User::factory()->create([
            User::FIELD_AVATAR => $path,
        ])->assignRole(Roles::USER->value);

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMINISTRATOR->value);

        $this->actingAs($user)
            ->get($url)
            ->assertOk();
    }

    private function invalidTwoFactorCode(string $secret): string
    {
        $currentCode = (string) Google2FA::getCurrentOtp($secret);

        return $currentCode === '000000' ? '111111' : '000000';
    }

    private function fakePrivateAvatar(): array
    {
        Storage::fake('private');

        $path = 'avatars/profile.jpg';
        Storage::disk('private')->put($path, 'avatar');

        return [
            $path,
            URL::temporarySignedRoute('files.serve', now()->addMinutes(5), [
                'path' => $path,
            ]),
        ];
    }
}
