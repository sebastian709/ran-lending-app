<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Remember-me cookie duration (minutes).
     */
    private const REMEMBER_ME_MINUTES = 60 * 24 * 15;
    private const SESSION_LOCK_TTL_DAYS = 30;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function authenticated(Request $request, $user)
    {
        $this->setSingleSessionLock($request, (int) $user->id);

        return redirect($user->is_admin ? '/admin/dashboard' : '/home');
    }

    /**
     * Attempt to log the user into the application.
     */
    protected function attemptLogin(Request $request)
    {
        if (method_exists($this->guard(), 'setRememberDuration')) {
            $this->guard()->setRememberDuration(self::REMEMBER_ME_MINUTES);
        }

        return $this->guard()->attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }

    /**
     * Add active-user guard to credentials.
     */
    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['status' => 1]
        );
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect(auth()->user()->is_admin ? '/admin/dashboard' : '/home');
        }

        return view('auth.login');
    }

    /**
     * Clear session lock on explicit logout.
     */
    protected function loggedOut(Request $request)
    {
        $this->clearSingleSessionLock($request);
    }

    private function setSingleSessionLock(Request $request, int $userId): void
    {
        Cache::put(
            $this->sessionLockKey($userId),
            $request->session()->getId(),
            now()->addDays(self::SESSION_LOCK_TTL_DAYS)
        );
    }

    private function clearSingleSessionLock(Request $request): void
    {
        $userId = $request->user()?->id;
        if (!$userId) {
            return;
        }

        $cacheKey = $this->sessionLockKey((int) $userId);
        $activeSessionId = Cache::get($cacheKey);

        if ($activeSessionId === $request->session()->getId()) {
            Cache::forget($cacheKey);
        }
    }

    private function sessionLockKey(int $userId): string
    {
        return "active_session:user:{$userId}";
    }

}
