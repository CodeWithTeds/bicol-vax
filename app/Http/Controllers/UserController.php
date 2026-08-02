<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\AdminNotification;
use App\Models\ScheduledReminder;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $patients = collect();
        $appointments = collect();

        if ($user) {
            $patients = Patient::where('email', $user->email)->latest()->get();
            $appointments = Appointment::where('user_id', $user->id)->latest()->get();
        }

        return view('user.dashboard', compact('patients', 'appointments'));
    }

    public function records()
    {
        $user = auth()->user();
        $patients = collect();
        $appointments = collect();

        if ($user) {
            $patients = Patient::where('email', $user->email)->latest()->get();
            $appointments = Appointment::where('user_id', $user->id)->latest()->get();
        }

        return view('user.records', compact('patients', 'appointments'));
    }

    public function profile()
    {
        $user = auth()->user();
        $patient = null;

        if ($user) {
            $patient = Patient::where('email', $user->email)->latest()->first();
        }

        return view('user.profile', compact('user', 'patient'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'age' => ['nullable', 'integer', 'min:1', 'max:150'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Update user name
        $user->name = $validated['name'];
        $user->save();

        // Update the linked patient record if it exists
        $patient = Patient::where('email', $user->email)->latest()->first();
        if ($patient) {
            $profileUpdates = [
                'full_name' => $validated['name'],
                'contact' => $validated['contact'] ?? $patient->contact,
                'address' => $validated['address'] ?? $patient->address,
                'gender' => $validated['gender'] ?? $patient->gender,
                'age' => $validated['age'] ?? $patient->age,
            ];

            if ($request->hasFile('profile_photo')) {
                $profileUpdates['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
            }

            $patient->update($profileUpdates);

            Appointment::where('user_id', $user->id)
                ->where(function ($query) {
                    $query->where('status', 'not_approved')
                        ->orWhereNull('status');
                })
                ->update([
                    'full_name' => $profileUpdates['full_name'],
                    'contact' => $profileUpdates['contact'],
                    'address' => $profileUpdates['address'],
                    'gender' => $profileUpdates['gender'],
                    'age' => $profileUpdates['age'],
                ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function booking()
    {
        $user = auth()->user();
        $patient = $user
            ? Patient::where('email', $user->email)->latest()->first()
            : null;

        return view('user.booking', compact('user', 'patient'));
    }

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['nullable', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            // Optional for user booking; generated automatically when omitted
            'card_no' => ['nullable', 'string', 'max:100'],
            'case_no' => ['nullable', 'string', 'max:100', 'unique:patients,case_no'],
            'contact' => ['nullable', 'string', 'max:50'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'email' => ['nullable', 'email', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:255'],
            'appointment_date' => ['required', 'date'],
            'parent_guardian' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'cat_category' => ['required', 'in:category_i,category_ii,category_iii'],
            'treatment' => ['nullable', 'array'],
            'treatment.*' => ['in:prprep,pep,booster,tet,erig,hrig'],
            'bite_type' => ['nullable', 'in:scratch,bite,lick_broken_skin,open_wound_exposure'],
            'place_of_bite' => ['required', 'in:hand,arm,leg,foot,face,neck,finger,multiple_sites'],
            'source' => ['required', 'in:dog,cat,bat,rat,monkey,other_animal'],
            'severity' => ['nullable', 'in:mild,moderate,severe'],
            'generic_name' => ['required', 'in:purified_vero_cell,purified_chick_embryo,human_diploid'],
            'route' => ['required', 'in:intramuscular,intradermal'],
            'brand_name' => ['required', 'in:verorab,speeda,rabiqur,abhayrab'],
            'dosage' => ['required', 'in:0_1ml,0_5ml,1_0ml'],
            'anti_rabies_dose' => ['required', 'in:day_0,day_3,day_7,day_14,day_28'],
            'anti_rabies_date' => ['nullable', 'date'],
            'tetanus_status' => ['required', 'in:valid,expired,unknown'],
            'tetanus_dose' => ['required', 'in:dose1,dose2,dose3'],
            'tetanus_date' => ['nullable', 'date'],
            'rabies_immunoglobulin' => ['required', 'in:erig,hrig,none'],
        ]);

        $validated['treatment_required'] = $request->input('treatment', []);
        unset($validated['treatment']);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        unset($validated['profile_photo']);

        $authUser = auth()->user();
        $profilePatient = $authUser
            ? Patient::where('email', $authUser->email)->latest()->first()
            : null;

        // Ensure DB-required fields have defaults when omitted from the form
        if (! array_key_exists('weight', $validated) || $validated['weight'] === null) {
            $validated['weight'] = 0.00;
        }

        // Auto-fill removed personal fields so booking submit still works.
        $validated['full_name'] = $validated['full_name'] ?? ($profilePatient?->full_name ?? $authUser->name ?? 'Online User');
        $validated['email'] = $validated['email'] ?? ($authUser->email ?? $profilePatient?->email);
        $validated['contact'] = $validated['contact'] ?? ($profilePatient?->contact ?? 'N/A');
        $validated['age'] = (int) ($validated['age'] ?? $profilePatient?->age ?? 18);
        $validated['birthday'] = $validated['birthday'] ?? now()->subYears(max($validated['age'], 1))->toDateString();
        $validated['gender'] = $validated['gender'] ?? ($profilePatient?->gender ?? 'other');
        $validated['address'] = $validated['address'] ?? ($profilePatient?->address ?? 'N/A');
        $validated['profile_photo_path'] = $validated['profile_photo_path'] ?? $profilePatient?->profile_photo_path;
        $validated['anti_rabies_date'] = $validated['anti_rabies_date'] ?? $validated['appointment_date'];
        $validated['tetanus_date'] = $validated['tetanus_date'] ?? $validated['appointment_date'];

        if (empty($validated['card_no'])) {
            $validated['card_no'] = 'CARD-' . now()->format('YmdHis');
        }

        if (empty($validated['case_no'])) {
            do {
                $candidate = 'CASE-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
            } while (Patient::where('case_no', $candidate)->exists());

            $validated['case_no'] = $candidate;
        }

        DB::transaction(function () use ($validated, $request) {
            Patient::create($validated);

            $user = null;
            if (auth()->check()) {
                $user = auth()->user();
            } elseif (!empty($validated['email'])) {
                $user = User::where('email', $validated['email'])->first();
            }

            Appointment::create([
                'user_id' => $user?->id,
                'full_name' => $validated['full_name'],
                'birthday' => $validated['birthday'],
                'age' => $validated['age'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'contact' => $validated['contact'],
                'appointment_date' => $validated['appointment_date'],
                'parent_guardian' => $validated['parent_guardian'] ?? null,
                'status' => 'not_approved',
            ]);
        });

        // Notify admin of new appointment request
        AdminNotification::newAppointmentRequest($validated['full_name']);

        return back()->with('success', 'Booking submitted successfully. It now appears in Admin Appointments for approval.');
    }

    public function myAppointments()
    {
        $user = auth()->user();
        $appointments = collect();
        if ($user) {
            $appointments = $user->appointments()->latest()->get();
        }

        return view('user.my-appointments', compact('appointments'));
    }

    public function reminders()
    {
        $user = auth()->user();
        $reminders = collect();

        if ($user) {
            Appointment::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereNotNull('appointment_date')
                ->get()
                ->each(function (Appointment $appointment) use ($user): void {
                    ScheduledReminder::syncForAppointment($appointment, $user->email);
                });

            $reminders = ScheduledReminder::where('user_id', $user->id)
                ->orderBy('reminder_date')
                ->orderBy('reminder_time')
                ->get();
        }

        return view('user.reminders', compact('reminders'));
    }

    public function registerPatient(Request $request)
    {
        // Require only email + password; make personal fields optional to avoid validation errors
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            // Require fullname and contact so admin gets basic info immediately
            'fullname' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:50'],
            'appointment_date' => ['nullable', 'date'],
            'parent_guardian' => ['nullable', 'string', 'max:255'],
        ]);

        $registration = DB::transaction(function () use ($validated) {
            $user = User::where('email', $validated['email'])->first();

            $providedPassword = $validated['password'];

            if ($user) {
                // update password
                $user->password = Hash::make($providedPassword);
                $user->save();
            } else {
                // keep users table minimal for privacy: use email local-part as display name
                $displayName = explode('@', $validated['email'])[0] ?? 'Patient';

                $user = User::create([
                    'name' => $displayName,
                    'email' => $validated['email'],
                    'password' => Hash::make($providedPassword),
                ]);
            }

            // store submitted personal details in patients so admins see them in Online Registrations
            $fullName = $validated['fullname'] ?? explode('@', $user->email)[0] ?? 'Patient';
            $birthday = $validated['birthday'] ?? now()->toDateString();
            $age = isset($validated['age']) ? (int) $validated['age'] : 18;
            $gender = $validated['gender'] ?? 'other';
            $address = $validated['address'] ?? 'N/A';
            $contact = $validated['contact'] ?? 'N/A';
            $appointmentDate = $validated['appointment_date'] ?? now()->toDateString();
            $parentGuardian = $validated['parent_guardian'] ?? null;

            // generate web-specific identifiers so admin can filter online registrations
            $cardNo = 'WEB-' . now()->format('YmdHis') . '-' . strtoupper(
                
                
                Str::random(4)
            );
            $caseNo = 'WEBCASE-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

            $patient = Patient::create([
                'full_name' => $fullName,
                'card_no' => $cardNo,
                'case_no' => $caseNo,
                'contact' => $contact,
                'age' => $age,
                'email' => $user->email,
                'gender' => $gender,
                'address' => $address,
                'weight' => 0.00,
                'source' => 'web',
                'cat_category' => 'category_i',
                'treatment_required' => null,
                'bite_type' => null,
                'place_of_bite' => 'multiple_sites',
                'source' => 'other_animal',
                'severity' => null,
                'generic_name' => 'purified_vero_cell',
                'route' => 'intramuscular',
                'brand_name' => 'verorab',
                'dosage' => '0_1ml',
                'anti_rabies_dose' => 'day_0',
                'anti_rabies_date' => $appointmentDate,
                'tetanus_status' => 'unknown',
                'tetanus_dose' => 'dose1',
                'tetanus_date' => $appointmentDate,
                'rabies_immunoglobulin' => 'none',
            ]);

            $appointment = Appointment::create([
                'user_id' => $user->id,
                'full_name' => $fullName,
                'birthday' => $birthday,
                'age' => $age,
                'gender' => $gender,
                'address' => $address,
                'contact' => $contact,
                'appointment_date' => $appointmentDate,
                'parent_guardian' => $parentGuardian,
                'status' => 'not_approved',
            ]);

            return [
                'patient' => $patient,
                'appointment' => $appointment,
            ];
        });

        // Notify admin of new online registration
        AdminNotification::newPatientRegistration($registration['patient']->full_name);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Registration submitted. The details are sent to Admin Online Registrations for review.',
                'patient' => $registration['patient'],
                'appointment' => $registration['appointment'],
            ], 201);
        }

        return back()->with('success', 'Registration submitted. The details are sent to Admin Online Registrations for review.');
    }

    public function login(Request $request)
    {
        if ($request->filled('code')) {
            $request->validate([
                'code' => ['required', 'string'],
            ]);

            $otpUserId = $request->session()->get('user_login_otp_user');
            $otpHash = $request->session()->get('user_login_otp_code_hash');
            $otpExpiresAt = $request->session()->get('user_login_otp_expires_at');

            if (! $otpUserId || ! $otpHash || ! $otpExpiresAt || Carbon::parse($otpExpiresAt)->isPast()) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Session expired, please login again.'], 419);
                }

                return back()->withErrors(['email' => 'Session expired, please login again.']);
            }

            if (! Hash::check($request->code, $otpHash)) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Invalid or expired OTP code.'], 422);
                }

                return back()->withErrors(['code' => 'Invalid or expired OTP code.']);
            }

            $user = User::find($otpUserId);
            if (! $user) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Account not found.'], 422);
                }

                return back()->withErrors(['email' => 'Account not found.']);
            }

            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->forget(['user_login_otp_user', 'user_login_otp_code_hash', 'user_login_otp_expires_at']);

            if ($request->expectsJson()) {
                return response()->json(['redirect' => route('user.dashboard')]);
            }

            return redirect()->intended(route('user.dashboard'));
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        // Check whether the user exists
        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid credentials'], 422);
            }

            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        }

        // Deny login if the user has no approved online registration yet
        $hasApproved = $user->patients()->where('status', 'approved')->exists() ?? false;
        if (! $hasApproved) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your account is pending admin approval. You cannot log in yet.'], 403);
            }

            return back()->withErrors(['email' => 'Your account is pending admin approval. You cannot log in yet.']);
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Invalid credentials'], 422);
            }

            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        }

        $code = (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        $request->session()->put('user_login_otp_user', $user->id);
        $request->session()->put('user_login_otp_code_hash', Hash::make($code));
        $request->session()->put('user_login_otp_expires_at', $expiresAt->toDateTimeString());

        Mail::to($user->email)->send(new OtpMail($code, $expiresAt, 'Your BicolVax login OTP'));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'OTP sent to your email.',
                'next' => 'otp',
            ]);
        }

        return back()->with('status', 'OTP sent to your email. Please check your inbox.');
    }
}
