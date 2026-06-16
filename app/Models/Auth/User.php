<?php

namespace App\Models\Auth;

use App\Models\Concerns\ProvidesFrontendIdentification;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

#[Table(name: self::TABLE, key: self::FIELD_ID, keyType: 'string', incrementing: false)]
#[Fillable([
    self::FIELD_NAME,
    self::FIELD_EMAIL,
    self::FIELD_PHONE,
    self::FIELD_DOCUMENT,
    self::FIELD_AVATAR,
])]
#[Hidden([
    self::FIELD_PASSWORD,
    'remember_token',
])]
class User extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    use HasUuids;
    use LogsActivity;
    use Notifiable;
    use ProvidesFrontendIdentification;
    use SoftDeletes;

    public const string TABLE = 'users';

    public const string FIELD_ID = 'id';

    public const string FIELD_NAME = 'name';

    public const string FIELD_EMAIL = 'email';

    public const string FIELD_EMAIL_VERIFIED_AT = 'email_verified_at';

    public const string FIELD_PHONE = 'phone';

    public const string FIELD_DOCUMENT = 'document';

    public const string FIELD_AVATAR = 'avatar';

    public const string FIELD_PASSWORD = 'password';

    public const string APPEND_FIELD_PERMISSIONS = 'permissions';

    public const string APPEND_FIELD_CURRENT_PASSWORD = 'current_password';

    public const string APPEND_FIELD_USER_ROLE = 'user_role';

    public const string APPEND_FIELD_PASSWORD = 'password_confirmation';

    protected function casts(): array
    {
        return [
            self::FIELD_EMAIL_VERIFIED_AT => 'datetime',
            self::FIELD_PASSWORD => 'hashed',
            'two_factor_secret' => 'encrypted',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([self::FIELD_NAME, self::FIELD_EMAIL, self::FIELD_PHONE, self::FIELD_DOCUMENT, self::FIELD_AVATAR])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_secret) && ! is_null($this->two_factor_confirmed_at);
    }
}
