<?php

namespace App\Models\Common;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Storage;

class Client extends BaseModel
{
    public const string TABLE = 'clients';

    public const string FIELD_ID = 'id';

    public const string FIELD_NAME = 'name';

    public const string FIELD_LOGO = 'logo';

    public const string FIELD_URL = 'url';

    protected $table = self::TABLE;

    /**
     * Resolve a URL pública do logo: assets estáticos (seed) ou disco público (upload).
     */
    public function logoUrl(): ?string
    {
        $logo = $this->{self::FIELD_LOGO};

        if (! $logo) {
            return null;
        }

        return str_starts_with($logo, 'assets/')
            ? asset($logo)
            : Storage::disk('public')->url($logo);
    }
}
