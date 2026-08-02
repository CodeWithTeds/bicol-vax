<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        if (! $user || ! $user->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'email' => 'Admin access only.',
            ]);
        }

        // Super admins should use the superadmin panel
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        if (! (bool) $request->session()->get('admin_otp_verified', false)) {
            return redirect()->route('admin.otp.verify');
        }

        return $next($request);
    }
}