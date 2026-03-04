<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleSession
{
    private const SESSION_LOCK_TTL_DAYS = 30;

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $userId = (int) Auth::id();
        $sessionId = $request->session()->getId();
        $cacheKey = $this->sessionLockKey($userId);
        $activeSessionId = Cache::get($cacheKey);

        // First request after login/cache reset: establish current session lock.
        if (!$activeSessionId) {
            Cache::put($cacheKey, $sessionId, now()->addDays(self::SESSION_LOCK_TTL_DAYS));
            return $next($request);
        }

        // If session differs, this is an old browser session.
        if (!hash_equals((string) $activeSessionId, (string) $sessionId)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You were logged out because your account was opened in another browser.',
                ], 401);
            }

            return redirect()
                ->route('login')
                ->with('session_conflict_message', 'You were logged out because your account was opened in another browser.')
                ->withErrors([
                    'email' => 'Your account is active in another browser. Please log in again.',
                ]);
        }

        // Touch the lock to keep it fresh.
        Cache::put($cacheKey, $sessionId, now()->addDays(self::SESSION_LOCK_TTL_DAYS));

        return $next($request);
    }

    private function sessionLockKey(int $userId): string
    {
        return "active_session:user:{$userId}";
    }
}
