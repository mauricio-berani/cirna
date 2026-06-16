<?php

namespace Tests\Feature\Auth;

use App\Enums\Auth\Permissions;
use App\Enums\Auth\Roles;
use App\Livewire\Auth\User\FormComponent;
use App\Livewire\Auth\User\IndexComponent;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use Database\Seeders\AdministratorUserSeeder;
use Database\Seeders\PermissionRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(PermissionRoleSeeder::class);
    }

    public function test_an_administrator_can_access_the_users_index_page(): void
    {
        $administrator = User::factory()->create();
        $administrator->assignRole(Roles::ADMINISTRATOR->value);

        $this->actingAs($administrator)
            ->get(route('users.index'))
            ->assertOk();
    }

    public function test_an_administrator_can_create_a_user_via_livewire(): void
    {
        $administrator = User::factory()->create();
        $administrator->assignRole(Roles::ADMINISTRATOR->value);

        $this->actingAs($administrator);

        Livewire::test(FormComponent::class)
            ->set('form.name', 'New User')
            ->set('form.email', 'new-user@example.com')
            ->set('form.phone', '(11) 99999-9999')
            ->set('form.user_role', Roles::USER->value)
            ->set('form.password', 'N0tLeaked!BliB-2026-xQ7#')
            ->set('form.password_confirmation', 'N0tLeaked!BliB-2026-xQ7#')
            ->call('create')
            ->assertHasNoErrors();

        $user = User::where('email', 'new-user@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('New User', $user->name);
        $this->assertTrue($user->hasRole(Roles::USER->value));
    }

    public function test_an_administrator_can_render_the_users_index_component(): void
    {
        $administrator = User::factory()->create();
        $administrator->assignRole(Roles::ADMINISTRATOR->value);

        $this->actingAs($administrator);

        Livewire::test(IndexComponent::class)
            ->assertOk()
            ->assertSee('Usuários');
    }

    public function test_a_regular_user_cannot_access_the_users_index_page(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Roles::USER->value);

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertForbidden();
    }

    public function test_an_administrator_can_edit_their_own_record_via_the_users_crud(): void
    {
        $administrator = User::factory()->create();
        $administrator->assignRole(Roles::ADMINISTRATOR->value);

        $this->actingAs($administrator);

        Livewire::test(FormComponent::class, ['itemId' => $administrator->getKey()])
            ->assertOk();
    }

    public function test_dashboard_requires_the_dashboard_mount_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();
    }

    public function test_user_index_sort_state_is_sanitized_before_querying(): void
    {
        $administrator = User::factory()->create();
        $administrator->assignRole(Roles::ADMINISTRATOR->value);

        $this->actingAs($administrator);

        Livewire::test(IndexComponent::class)
            ->set('sortBy', ['column' => 'password', 'direction' => 'drop table'])
            ->assertSet('sortBy.column', User::FIELD_NAME)
            ->assertSet('sortBy.direction', 'asc')
            ->assertOk();
    }

    public function test_permission_role_seeder_resyncs_existing_roles(): void
    {
        $role = Role::where('name', Roles::USER->value)->firstOrFail();
        $role->syncPermissions([]);

        $this->seed(PermissionRoleSeeder::class);

        $this->assertTrue($role->fresh()->hasPermissionTo(Permissions::MOUNT_DASHBOARD->value));
    }

    public function test_administrator_user_seeder_creates_missing_admin_with_password(): void
    {
        config([
            'admin.email' => 'admin@example.com',
            'admin.name' => 'Administrator',
            'admin.phone' => '(11) 99999-9999',
            'admin.password' => 'N0tLeaked!BliB-2026-xQ7#',
        ]);

        $this->seed(AdministratorUserSeeder::class);

        $administrator = User::where(User::FIELD_EMAIL, 'admin@example.com')->first();

        $this->assertNotNull($administrator);
        $this->assertSame('Administrator', $administrator->{User::FIELD_NAME});
        $this->assertTrue(Hash::check('N0tLeaked!BliB-2026-xQ7#', $administrator->{User::FIELD_PASSWORD}));
        $this->assertTrue($administrator->hasRole(Roles::ADMINISTRATOR->value));
    }

    public function test_administrator_user_seeder_updates_existing_admin_without_deleting_it(): void
    {
        config([
            'admin.email' => 'admin@example.com',
            'admin.name' => 'Updated Admin',
            'admin.phone' => '(11) 99999-9999',
            'admin.password' => 'N0tLeaked!BliB-2026-xQ7#',
        ]);

        $administrator = User::factory()->create([
            User::FIELD_EMAIL => 'admin@example.com',
            User::FIELD_NAME => 'Existing Admin',
        ]);

        $this->seed(AdministratorUserSeeder::class);

        $administrator->refresh();

        $this->assertSame('Updated Admin', $administrator->{User::FIELD_NAME});
        $this->assertSame('(11) 99999-9999', $administrator->{User::FIELD_PHONE});
        $this->assertTrue($administrator->hasRole(Roles::ADMINISTRATOR->value));
        $this->assertSame(1, User::where(User::FIELD_EMAIL, 'admin@example.com')->count());
    }
}
