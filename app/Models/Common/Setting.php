<?php

namespace App\Models\Common;

use App\Models\BaseModel;

class Setting extends BaseModel
{
    public const string TABLE = 'settings';

    public const string FIELD_ID = 'id';

    public const string FIELD_KEY = 'key';

    public const string FIELD_VALUE = 'value';

    public const string KEY_CAREERS_EMAIL = 'careers_email';

    public const string KEY_ISO_CERTIFICATE = 'iso_certificate_path';

    public const string KEY_SHOW_CLIENTS = 'show_clients_section';

    protected $table = self::TABLE;

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = static::query()->where(self::FIELD_KEY, $key)->value(self::FIELD_VALUE);

        return $value !== null && $value !== '' ? $value : $default;
    }

    public static function put(string $key, ?string $value): void
    {
        $setting = static::query()->where(self::FIELD_KEY, $key)->first() ?? new static;

        $setting->forceFill([
            self::FIELD_KEY => $key,
            self::FIELD_VALUE => $value,
        ])->save();
    }

    /**
     * E-mail de destino das candidaturas: valor do painel ou fallback do .env.
     */
    public static function careersEmail(): string
    {
        return static::get(self::KEY_CAREERS_EMAIL) ?: (string) config('client.contact_email');
    }

    public static function isoCertificatePath(): ?string
    {
        return static::get(self::KEY_ISO_CERTIFICATE);
    }

    /**
     * Exibir a seção de clientes na home? Oculta por padrão.
     */
    public static function showClientsSection(): bool
    {
        return static::get(self::KEY_SHOW_CLIENTS) === '1';
    }
}
