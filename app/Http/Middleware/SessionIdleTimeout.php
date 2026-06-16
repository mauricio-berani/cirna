<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionIdleTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        $timeout = config('session.idle_timeout', 30);
        $lastActivity = session('last_activity_time');

        if ($lastActivity && now()->diffInMinutes($lastActivity) >= $timeout) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('message', __('auth.session_expired'));
        }

        session(['last_activity_time' => now()]);

        return $next($request);
    }
}
