<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), browsing-topics=()');
        $response->headers->set(
            'Content-Security-Policy',
            implode('; ', $this->contentSecurityPolicyDirectives())
        );

        if ($request->isSecure() && app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    private function contentSecurityPolicyDirectives(): array
    {
        $viteOrigins = $this->viteOrigins();
        $nonce = Vite::cspNonce();

        $scriptSrc = array_merge(["'self'"], $viteOrigins);
        $styleSrc = array_merge(["'self'"], $viteOrigins);
        $imgSrc = array_merge(["'self'", 'data:', 'blob:'], $viteOrigins);
        $connectSrc = array_merge(["'self'"], $viteOrigins, $this->viteWebsocketOrigins());

        // Alpine.js (embutido no Livewire e usado pelo MaryUI) avalia expressões
        // de string como JS em runtime via new Function(), o que exige 'unsafe-eval'
        // no script-src tanto em produção quanto em desenvolvimento.
        $scriptSrc[] = "'unsafe-eval'";

        if (! app()->isProduction()) {
            $styleSrc[] = "'unsafe-inline'";
        }

        if ($nonce) {
            $scriptSrc[] = sprintf("'nonce-%s'", $nonce);
            $styleSrc[] = sprintf("'nonce-%s'", $nonce);
        }

        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "object-src 'none'",
            'img-src '.implode(' ', array_unique($imgSrc)),
            'style-src '.implode(' ', array_unique($styleSrc)),
            'script-src '.implode(' ', array_unique($scriptSrc)),
            'connect-src '.implode(' ', array_unique($connectSrc)),
            "font-src 'self' data:",
        ];

        if (app()->isProduction()) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return $directives;
    }

    private function viteOrigins(): array
    {
        if (! app()->isLocal()) {
            return [];
        }

        $hosts = array_filter([
            env('VITE_HMR_HOST'),
            'localhost',
            '127.0.0.1',
        ]);

        return collect($hosts)
            ->flatMap(fn (string $host) => [
                sprintf('http://%s:5173', $host),
                sprintf('https://%s:5173', $host),
            ])
            ->unique()
            ->values()
            ->all();
    }

    private function viteWebsocketOrigins(): array
    {
        if (! app()->isLocal()) {
            return [];
        }

        $hosts = array_filter([
            env('VITE_HMR_HOST'),
            'localhost',
            '127.0.0.1',
        ]);

        return collect($hosts)
            ->flatMap(fn (string $host) => [
                sprintf('ws://%s:5173', $host),
                sprintf('wss://%s:5173', $host),
            ])
            ->unique()
            ->values()
            ->all();
    }
}
