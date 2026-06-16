<?php

namespace App\Models;

use App\Models\Concerns\ProvidesFrontendIdentification;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BaseModel extends Model
{
    use HasFactory;
    use HasUuids;
    use ProvidesFrontendIdentification;
    use SoftDeletes;

    protected $guarded = ['*'];

    protected const string DEFAULT_FIELD_ID = 'id';

    protected const string DEFAULT_DESCRIPTION_ID = 'name';

    public static function getOptions(
        string $fieldId = self::DEFAULT_FIELD_ID,
        string $fieldDescription = self::DEFAULT_DESCRIPTION_ID,
        ?callable $filter = null
    ): array {
        $query = static::query()
            ->select([$fieldId, $fieldDescription])
            ->orderBy($fieldDescription);

        if ($filter) {
            $query = $filter($query);
        }

        return collect([
            ['value' => null, 'label' => __('interface.select.option')],
        ])->merge(
            $query->toBase()
                ->get()
                ->map(fn ($row) => ['value' => $row->{$fieldId}, 'label' => $row->{$fieldDescription}])
        )->toArray();
    }
}
