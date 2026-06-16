<?php

namespace App\Services\Auth;

use App\Contracts\Auth\BuildsPermissionOptions;
use App\Enums\Auth\Permissions;
use Illuminate\Support\Str;

class BuildPermissionOptionsService implements BuildsPermissionOptions
{
    public function handle(): array
    {
        $actionOrder = [
            'mount' => 1,
            'read' => 2,
            'create' => 3,
            'update' => 4,
            'delete' => 5,
        ];

        $actionLabels = [
            'mount' => __('permissions.actions.mount'),
            'read' => __('permissions.actions.read'),
            'create' => __('permissions.actions.create'),
            'update' => __('permissions.actions.update'),
            'delete' => __('permissions.actions.delete'),
        ];

        $grouped = [];

        foreach (Permissions::cases() as $permission) {
            $parts = explode('_', $permission->value);
            $action = array_shift($parts) ?? '';
            $resource = $parts ? implode('_', $parts) : $permission->value;
            $groupLabel = __(
                sprintf('permissions.groups.%s', $resource),
                [],
                app()->getLocale()
            );

            if ($groupLabel === sprintf('permissions.groups.%s', $resource)) {
                $groupLabel = Str::of($resource)->replace('_', ' ')->title()->toString();
            }

            $grouped[$groupLabel][] = [
                'value' => $permission->value,
                'label' => $actionLabels[$action] ?? Str::of($action)->title()->toString(),
                'action' => $action,
            ];
        }

        ksort($grouped);

        return collect($grouped)
            ->map(function (array $items, string $group) use ($actionOrder) {
                usort($items, function (array $left, array $right) use ($actionOrder) {
                    $leftOrder = $actionOrder[$left['action']] ?? 99;
                    $rightOrder = $actionOrder[$right['action']] ?? 99;

                    return $leftOrder <=> $rightOrder;
                });

                return [
                    'group' => $group,
                    'items' => $items,
                ];
            })
            ->values()
            ->all();
    }
}
