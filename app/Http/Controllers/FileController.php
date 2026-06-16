<?php

namespace App\Http\Controllers;

use App\Models\Auth\User;
use App\Models\Recruitment\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    private const array IMAGE_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    private const array DOCUMENT_MIME_TYPES = [
        'application/pdf',
    ];

    public function serve(Request $request, string $path): Response
    {
        $normalizedPath = trim(str_replace(['..', '\\'], '', $path), '/');

        if (
            $normalizedPath !== $path
            || ! $this->canServePath($request, $normalizedPath)
            || ! Storage::disk('private')->exists($normalizedPath)
        ) {
            abort(404);
        }

        abort_unless(
            in_array(
                Storage::disk('private')->mimeType($normalizedPath),
                $this->allowedMimeTypesForPath($normalizedPath),
                true
            ),
            403
        );

        return Storage::disk('private')->response($normalizedPath);
    }

    private function allowedMimeTypesForPath(string $path): array
    {
        return Str::startsWith($path, 'applications/')
            ? self::DOCUMENT_MIME_TYPES
            : self::IMAGE_MIME_TYPES;
    }

    private function canServePath(Request $request, string $path): bool
    {
        $user = $request->user();

        if (! $user instanceof User) {
            return false;
        }

        if (Str::startsWith($path, 'avatars/')) {
            return $this->canServeAvatar($user, $path);
        }

        if (Str::startsWith($path, 'applications/')) {
            return $this->canServeApplicationResume($user, $path);
        }

        return false;
    }

    private function canServeAvatar(User $user, string $path): bool
    {
        if (! User::query()->where(User::FIELD_AVATAR, $path)->exists()) {
            return false;
        }

        return $user->{User::FIELD_AVATAR} === $path
            || Gate::forUser($user)->allows('read', User::class);
    }

    private function canServeApplicationResume(User $user, string $path): bool
    {
        if (! Application::query()->where(Application::FIELD_RESUME_PATH, $path)->exists()) {
            return false;
        }

        return Gate::forUser($user)->allows('read', Application::class);
    }
}
