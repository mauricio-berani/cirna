<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use RuntimeException;

trait ManagesFilesTrait
{
    protected array $allowedUploadMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    protected int $maxUploadSizeBytes = 5 * 1024 * 1024; // 5MB

    /**
     * Disco de armazenamento. Privado por padrão (servido via URL assinada);
     * use 'public' para arquivos exibidos no site institucional (ex.: logos).
     */
    protected string $uploadDisk = 'private';

    public function uploadFile(mixed $file, ?string $existingFilePath = null, bool $unique = false): ?string
    {
        if (! $file) {
            return null;
        }

        if (! $file instanceof UploadedFile || ! $file->isValid()) {
            throw new RuntimeException(__('services.files.invalid'));
        }

        if (! in_array($file->getMimeType(), $this->allowedUploadMimeTypes, true)) {
            throw new RuntimeException(__('services.files.invalid_type'));
        }

        if ($file->getSize() > $this->maxUploadSizeBytes) {
            throw new RuntimeException(__('services.files.too_large'));
        }

        if ($unique && $existingFilePath && $this->isManagedFilePath($existingFilePath)) {
            $this->deleteFile($existingFilePath);
        }

        $directory = $this->normalizedFileDirectory();
        $fileName = $this->generateFileSlug($file->hashName(), $file->extension());

        return $file->storeAs($directory, $fileName, $this->uploadDisk);
    }

    public function deleteFile(string $filePath): bool
    {
        if (! $this->isManagedFilePath($filePath)) {
            return false;
        }

        return Storage::disk($this->uploadDisk)->delete($filePath);
    }

    protected function generateFileSlug(string $fileName, string $extension): string
    {
        return Str::slug(pathinfo($fileName, PATHINFO_FILENAME)).'-'.time().'.'.$extension;
    }

    /**
     * Confere a assinatura mágica do arquivo (%PDF-) além do MIME/extensão.
     */
    public function hasValidPdfSignature(?string $realPath): bool
    {
        if (! $realPath) {
            return false;
        }

        $handle = @fopen($realPath, 'rb');

        if ($handle === false) {
            return false;
        }

        $signature = (string) fread($handle, 5);
        fclose($handle);

        return $signature === '%PDF-';
    }

    public function getFileUrl(?string $filePath): ?string
    {
        if (! $filePath || ! $this->isManagedFilePath($filePath)) {
            return null;
        }

        return URL::temporarySignedRoute(
            'files.serve',
            now()->addMinutes(30),
            ['path' => $filePath]
        );
    }

    protected function normalizedFileDirectory(): string
    {
        return trim(str_replace(['..', '\\'], '', $this->filePath), '/');
    }

    protected function isManagedFilePath(string $filePath): bool
    {
        $directory = $this->normalizedFileDirectory();
        $normalizedPath = trim(str_replace(['..', '\\'], '', $filePath), '/');

        return $directory !== '' && str_starts_with($normalizedPath, $directory.'/');
    }
}
