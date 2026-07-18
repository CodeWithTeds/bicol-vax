<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if ((bool) request()->session()->get('admin_otp_verified', false)) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('admin.otp.verify');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $adminEmails = User::query()
            ->where('is_admin', true)
            ->pluck('email')
            ->filter()
            ->values()
            ->all();

        if (empty($adminEmails)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No admin account is configured.',
                ], 422);
            }

            return back()->withErrors([
                'email' => 'No admin account is configured.',
            ])->withInput();
        }

        $request->validate([
            'email' => ['required', 'email', Rule::in($adminEmails)],
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 422);
            }

            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        if (! $user->is_admin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Not authorized as admin',
                ], 403);
            }

            return back()->withErrors(['email' => 'Not authorized as admin']);
        }

        if (! $request->expectsJson()) {
            Auth::login($user);
            $request->session()->put('admin_otp_verified', true);
            $request->session()->forget('admin_otp_user');

            return redirect()->route('admin.dashboard');
        }

        // generate OTP
        $code = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        DB::table('admin_otps')->insert([
            'user_id' => $user->id,
            'code' => (string) $code,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // send email
        Mail::to($user->email)->send(new OtpMail($code, $expiresAt));

        // store user id in session for verification
        $request->session()->forget('admin_otp_verified');
        $request->session()->put('admin_otp_user', $user->id);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'OTP sent to your email',
                'next' => 'otp',
            ]);
        }

        return redirect()->route('admin.otp.verify')->with('status', 'OTP sent to your email');
    }

    public function showVerify()
    {
        return view('admin.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $userId = $request->session()->get('admin_otp_user');
        if (! $userId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Session expired, please login again',
                ], 419);
            }

            return redirect()->route('admin.login')->withErrors(['email' => 'Session expired, please login again']);
        }

        $record = DB::table('admin_otps')
            ->where('user_id', $userId)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $record) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Invalid or expired code',
                ], 422);
            }

            return back()->withErrors(['code' => 'Invalid or expired code']);
        }

        // login user
        $user = User::find($userId);
        Auth::login($user);
        $request->session()->put('admin_otp_verified', true);

        // cleanup
        DB::table('admin_otps')->where('user_id', $userId)->delete();
        $request->session()->forget('admin_otp_user');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'OTP verified',
                'redirect' => route('admin.dashboard'),
            ]);
        }

        return redirect()->route('admin.dashboard');
    }
}
