<?php

namespace App\Http\Controllers;

use App\Models\Common\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CertificateController extends Controller
{
    /**
     * Serve publicamente o certificado ISO configurado no painel (somente leitura).
     */
    public function download(): Response
    {
        $path = Setting::isoCertificatePath();

        abort_if(! $path, 404);

        $normalizedPath = trim(str_replace(['..', '\\'], '', $path), '/');

        abort_unless(
            $normalizedPath === $path
            && Str::startsWith($normalizedPath, 'certificates/')
            && Storage::disk('private')->exists($normalizedPath)
            && Storage::disk('private')->mimeType($normalizedPath) === 'application/pdf',
            404
        );

        return Storage::disk('private')->response(
            $normalizedPath,
            'certificado-iso-cirna.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
