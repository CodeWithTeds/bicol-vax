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
            // Personal info
            'full_name'        => ['nullable', 'string', 'max:255'],
            'birthday'         => ['nullable', 'date'],
            'age'              => ['nullable', 'integer', 'min:0', 'max:150'],
            'gender'           => ['nullable', 'in:male,female,other'],
            'address'          => ['nullable', 'string', 'max:255'],
            'contact'          => ['nullable', 'string', 'max:50'],
            'email'            => ['nullable', 'email', 'max:255'],
            'parent_guardian'  => ['nullable', 'string', 'max:255'],
            'appointment_date' => ['required', 'date'],
            'profile_photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // Animal bite info
            'animal_type'      => ['nullable', 'string', 'max:50'],
            'pet_or_stray'     => ['nullable', 'in:pet,stray'],
            'vaccinated_animal'=> ['nullable', 'in:yes,no'],
            'animal_status'    => ['nullable', 'string', 'max:255'],
            'date_of_bite'     => ['nullable', 'date'],
            'place_of_bite'    => ['nullable', 'in:hand,arm,leg,foot,face,neck,finger,multiple_sites'],
            'severity'         => ['nullable', 'in:mild,moderate,severe'],
            'washing_of_wound' => ['nullable', 'in:yes,no'],
            'tandok_tambal'    => ['nullable', 'in:yes,no'],
            'owner_name'       => ['nullable', 'string', 'max:255'],
            'owner_address'    => ['nullable', 'string', 'max:255'],
            // Medical history
            'weight'           => ['nullable', 'numeric', 'min:0'],
            'blood_pressure'   => ['nullable', 'string', 'max:20'],
            'temperature'      => ['nullable', 'string', 'max:20'],
            'allergy'          => ['nullable', 'string', 'max:255'],
        ]);

        // Medical history booleans
        $validated['has_diabetes']         = $request->boolean('has_diabetes');
        $validated['has_cancer']           = $request->boolean('has_cancer');
        $validated['has_organ_transplant'] = $request->boolean('has_organ_transplant');
        $validated['has_ckd']              = $request->boolean('has_ckd');
        $validated['has_hiv']              = $request->boolean('has_hiv');
        $validated['taking_steroid']       = $request->boolean('taking_steroid');
        $validated['has_riv']              = $request->boolean('has_riv');

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        unset($validated['profile_photo']);

        $authUser = auth()->user();
        $profilePatient = $authUser
            ? Patient::where('email', $authUser->email)->latest()->first()
            : null;

        $branchId = $authUser?->branch_id
            ?? Patient::where('email', $authUser?->email ?? '')
                ->whereNotNull('branch_id')
                ->oldest()
                ->value('branch_id');

        // Auto-fill personal fields from profile
        $validated['full_name']  = $validated['full_name']  ?? ($profilePatient?->full_name  ?? $authUser?->name ?? 'Online User');
        $validated['email']      = $validated['email']      ?? ($authUser?->email             ?? $profilePatient?->email);
        $validated['contact']    = $validated['contact']    ?? ($profilePatient?->contact      ?? 'N/A');
        $validated['age']        = (int)($validated['age']  ?? $profilePatient?->age           ?? 18);
        $validated['birthday']   = $validated['birthday']   ?? now()->subYears(max($validated['age'], 1))->toDateString();
        $validated['gender']     = $validated['gender']     ?? ($profilePatient?->gender        ?? 'other');
        $validated['address']    = $validated['address']    ?? ($profilePatient?->address       ?? 'N/A');
        $validated['weight']     = $validated['weight']     ?? $profilePatient?->weight         ?? 0.00;
        $validated['profile_photo_path'] = $validated['profile_photo_path'] ?? $profilePatient?->profile_photo_path;

        // Admin-filled clinical defaults (placeholder values — nurse fills later)
        $validated['cat_category']         = 'category_i';
        $validated['place_of_bite']        = $validated['place_of_bite'] ?? 'multiple_sites';
        $validated['source']               = $validated['animal_type']   ?? 'other_animal';
        $validated['generic_name']         = 'purified_vero_cell';
        $validated['route']                = 'intramuscular';
        $validated['brand_name']           = 'verorab';
        $validated['dosage']               = '0_1ml';
        $validated['anti_rabies_dose']     = 'day_0';
        $validated['anti_rabies_date']     = $validated['appointment_date'];
        $validated['tetanus_status']       = 'unknown';
        $validated['tetanus_dose']         = 'dose1';
        $validated['tetanus_date']         = $validated['appointment_date'];
        $validated['rabies_immunoglobulin']= 'none';
        $validated['treatment_required']   = [];
        $validated['bite_type']            = null;

        // Auto-generate card/case numbers
        if (empty($validated['card_no'])) {
            $validated['card_no'] = 'CARD-' . now()->format('YmdHis');
        }
        if (empty($validated['case_no'])) {
            do {
                $candidate = 'CASE-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
            } while (Patient::where('case_no', $candidate)->exists());
            $validated['case_no'] = $candidate;
        }

        DB::transaction(function () use ($validated, $branchId) {
            $patient = Patient::create(array_merge($validated, ['branch_id' => $branchId]));

            $user = auth()->check() ? auth()->user() : User::where('email', $validated['email'])->first();

            Appointment::create([
                'branch_id'        => $branchId,
                'user_id'          => $user?->id,
                'patient_id'       => $patient->id,
                'full_name'        => $validated['full_name'],
                'birthday'         => $validated['birthday'],
                'age'              => $validated['age'],
                'gender'           => $validated['gender'],
                'address'          => $validated['address'],
                'contact'          => $validated['contact'],
                'appointment_date' => $validated['appointment_date'],
                'parent_guardian'  => $validated['parent_guardian'] ?? null,
                'status'           => 'not_approved',
            ]);
        });

        AdminNotification::newAppointmentRequest($validated['full_name']);

        return back()->with('success', 'Booking submitted successfully. Your appointment is pending approval.');
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
            'email'            => ['required', 'email', 'max:255'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
            'fullname'         => ['required', 'string', 'max:255'],
            'birthday'         => ['nullable', 'date'],
            'age'              => ['nullable', 'integer', 'min:1', 'max:120'],
            'gender'           => ['nullable', 'in:male,female,other'],
            'address'          => ['nullable', 'string', 'max:255'],
            'contact'          => ['required', 'string', 'max:50'],
            'appointment_date' => ['nullable', 'date'],
            'parent_guardian'  => ['nullable', 'string', 'max:255'],
            'branch_id'        => ['required', 'exists:branches,id'],
        ]);

        // One account per person: reject if email already registered
        if (User::where('email', $validated['email'])->exists()) {
            $message = 'An account with this email already exists. Please log in instead.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }
            return back()->withErrors(['email' => $message])->withInput();
        }

        // Also check if a patient record with this email already exists
        if (Patient::where('email', $validated['email'])->exists()) {
            $message = 'A patient record with this email already exists. Please contact the clinic.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }
            return back()->withErrors(['email' => $message])->withInput();
        }

        $registration = DB::transaction(function () use ($validated) {
            $branchId = (int) $validated['branch_id'];

            $providedPassword = $validated['password'];

            // Create new user account
            $displayName = explode('@', $validated['email'])[0] ?? 'Patient';

            $user = User::create([
                'name' => $displayName,
                'email' => $validated['email'],
                'password' => Hash::make($providedPassword),
            ]);

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
                'branch_id'  => $branchId,
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
                'branch_id'       => $branchId,
                'user_id'         => $user->id,
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

            if ($user->is_admin || $user->is_super_admin) {
                $request->session()->put('admin_otp_verified', true);
                $request->session()->put('admin_branch_id', $user->branch_id);

                $redirect = $user->is_super_admin
                    ? route('superadmin.dashboard')
                    : route('admin.dashboard');

                if ($request->expectsJson()) {
                    return response()->json(['redirect' => $redirect]);
                }

                return redirect()->intended($redirect);
            }

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

        // Admin/super-admin accounts (created by super admin) are not subject to
        // online registration approval, so skip the patient approval check for them.
        $isStaff = (bool) ($user->is_admin || $user->is_super_admin);

        // Deny login if the user has no approved online registration yet
        $hasApproved = $user->patients()->where('status', 'approved')->exists() ?? false;
        if (! $hasApproved && ! $isStaff) {
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
