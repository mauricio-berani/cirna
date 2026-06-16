<?php

namespace App\Models\Recruitment;

use App\Enums\Recruitment\ApplicationArea;
use App\Models\BaseModel;

class Application extends BaseModel
{
    public const string TABLE = 'applications';

    public const string FIELD_ID = 'id';

    public const string FIELD_NAME = 'name';

    public const string FIELD_EMAIL = 'email';

    public const string FIELD_PHONE = 'phone';

    public const string FIELD_AREA = 'area';

    public const string FIELD_RESUME_PATH = 'resume_path';

    public const string FIELD_CREATED_AT = 'created_at';

    protected $table = self::TABLE;

    protected function casts(): array
    {
        return [
            self::FIELD_AREA => ApplicationArea::class,
        ];
    }

    public function areaLabel(): string
    {
        return $this->{self::FIELD_AREA}?->label() ?? '—';
    }
}
