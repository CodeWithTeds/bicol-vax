<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\AdminNotification;
use App\Models\ScheduledReminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PatientApprovedMail;
use App\Mail\ScheduledReminderMail;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ──────────────────────────────────────────────
    // Branch-scoped query helpers
    // ──────────────────────────────────────────────

    /**
     * Returns the branch_id of the currently logged-in admin.
     * null means the admin has no branch (shouldn't happen for branch admins).
     */
    private function branchId(): ?int
    {
        return Auth::user()?->branch_id;
    }

    /**
     * Base Patient query scoped to the admin's branch.
     */
    private function patientQuery()
    {
        return Patient::where('branch_id', $this->branchId());
    }

    /**
     * Base Appointment query scoped to the admin's branch.
     */
    private function appointmentQuery()
    {
        return Appointment::where('branch_id', $this->branchId());
    }

    // ──────────────────────────────────────────────
    // Appointment helpers
    // ──────────────────────────────────────────────

    private function findRelatedPatient(Appointment $appointment): ?Patient
    {
        $branchId = $this->branchId();
        $email    = $appointment->user?->email;

        if (! empty($email)) {
            $patient = Patient::where('branch_id', $branchId)
                ->where('email', $email)
                ->latest()->first();
            if ($patient) return $patient;
        }

        $patient = Patient::where('branch_id', $branchId)
            ->where('full_name', $appointment->full_name)
            ->when($appointment->contact, fn ($q) => $q->where('contact', $appointment->contact))
            ->latest()->first();

        return $patient ?? Patient::where('branch_id', $branchId)
            ->where('full_name', $appointment->full_name)
            ->latest()->first();
    }

    private function buildAppointmentPayload(Appointment $appointment): array
    {
        $patient = $this->findRelatedPatient($appointment);

        return [
            'id'                 => $appointment->id,
            'patient'            => $appointment->full_name,
            'case_no'            => $patient?->case_no,
            'card_no'            => $patient?->card_no,
            'patient_source'     => $patient?->source,
            'patient_status'     => $patient?->status,
            'profile_photo_url'  => $patient?->profile_photo_path
                ? '/storage/' . ltrim($patient->profile_photo_path, '/')
                : null,
            'email'              => $appointment->user?->email ?? $patient?->email,
            'birthday'           => optional($appointment->birthday)->format('M d, Y'),
            'birthday_raw'       => optional($appointment->birthday)->format('Y-m-d'),
            'age'                => $appointment->age,
            'gender'             => ucfirst($appointment->gender),
            'address'            => $appointment->address,
            'contact'            => $appointment->contact,
            'appointment_date'   => optional($appointment->appointment_date)->format('M d, Y'),
            'appointment_date_raw' => optional($appointment->appointment_date)->format('Y-m-d'),
            'appointment_time'   => $appointment->appointment_time,
            'status'             => $appointment->status,
            'registered'         => optional($appointment->created_at)->format('M d, Y h:i A'),
            'statusUpdateUrl'    => route('admin.appointments.status', $appointment),
            'deleteUrl'          => route('admin.appointments.destroy', $appointment),
        ];
    }

    // ──────────────────────────────────────────────
    // Dashboard
    // ──────────────────────────────────────────────

    public function dashboard()
    {
        $totalPatients        = $this->patientQuery()->count();
        $patientsToday        = $this->patientQuery()->whereDate('created_at', today())->count();
        $pendingPatients      = $this->patientQuery()->where(fn ($q) => $q->where('status', 'not_approved')->orWhereNull('status'))->count();
        $approvedPatients     = $this->patientQuery()->where('status', 'approved')->count();
        $severeCases          = $this->patientQuery()->where('severity', 'severe')->count();
        $categoryICases       = $this->patientQuery()->where('cat_category', 'category_i')->count();
        $categoryIICases      = $this->patientQuery()->where('cat_category', 'category_ii')->count();
        $categoryIIICases     = $this->patientQuery()->where('cat_category', 'category_iii')->count();

        $totalAppointments    = $this->appointmentQuery()->count();
        $pendingAppointments  = $this->appointmentQuery()->where(fn ($q) => $q->where('status', 'not_approved')->orWhereNull('status'))->count();
        $approvedAppointments = $this->appointmentQuery()->where('status', 'approved')->count();
        $completedAppointments = $this->appointmentQuery()->whereIn('status', ['completed', 'done'])->count();
        $appointmentsToday    = $this->appointmentQuery()->whereDate('appointment_date', today())->count();

        $recentPatients       = $this->patientQuery()->latest()->take(5)->get();
        $recentAppointments   = $this->appointmentQuery()->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPatients', 'patientsToday', 'pendingPatients', 'approvedPatients',
            'severeCases', 'categoryICases', 'categoryIICases', 'categoryIIICases',
            'totalAppointments', 'pendingAppointments', 'approvedAppointments',
            'completedAppointments', 'appointmentsToday',
            'recentPatients', 'recentAppointments'
        ));
    }

    // ──────────────────────────────────────────────
    // Patients
    // ──────────────────────────────────────────────

    public function patients()
    {
        $patients = $this->patientQuery()->where('source', 'admin')->latest()->get();

        $onlineRegistrations = $this->patientQuery()->where(function ($q) {
            $q->where('source', 'web')->orWhere('card_no', 'like', 'WEB-%');
        })->latest()->get();

        $approvedOnlineRegistrations = $onlineRegistrations->where('status', 'approved')->values();

        $allPatients   = $this->patientQuery()->latest()->get();
        $totalPatients = $allPatients->count();

        $appointments = $this->appointmentQuery()->latest()->get();

        $ongoingAppointments = $appointments->filter(function (Appointment $a) {
            return ($a->status === 'approved')
                && (optional($a->appointment_date)->isToday()
                    || (optional($a->appointment_date) && optional($a->appointment_date)->greaterThanOrEqualTo(now()->startOfDay())));
        })->values();

        $missedAppointments = $appointments->filter(function (Appointment $a) {
            return ($a->status === 'approved')
                && optional($a->appointment_date)?->lt(now()->startOfDay());
        })->values();

        $completedAppointments = $appointments->filter(function (Appointment $a) {
            return in_array($a->status, ['completed', 'done'], true);
        })->values();

        return view('admin.patients', compact(
            'patients', 'onlineRegistrations', 'approvedOnlineRegistrations',
            'allPatients', 'totalPatients',
            'appointments', 'ongoingAppointments', 'missedAppointments', 'completedAppointments'
        ));
    }

    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'full_name'             => ['required', 'string', 'max:255'],
            'card_no'               => ['required', 'string', 'max:100'],
            'case_no'               => ['required', 'string', 'max:100', 'unique:patients,case_no'],
            'contact'               => ['required', 'string', 'max:50'],
            'age'                   => ['required', 'integer', 'min:0', 'max:150'],
            'email'                 => ['nullable', 'email', 'max:255'],
            'gender'                => ['required', 'in:male,female,other'],
            'address'               => ['required', 'string', 'max:255'],
            'weight'                => ['required', 'numeric', 'min:0'],
            'cat_category'          => ['required', 'in:category_i,category_ii,category_iii'],
            'treatment'             => ['nullable', 'array'],
            'treatment.*'           => ['in:prprep,pep,booster,tet,erig,hrig'],
            'bite_type'             => ['nullable', 'in:scratch,bite,lick_broken_skin,open_wound_exposure'],
            'place_of_bite'         => ['required', 'in:hand,arm,leg,foot,face,neck,finger,multiple_sites'],
            'source'                => ['required', 'in:dog,cat,bat,rat,monkey,other_animal'],
            'severity'              => ['nullable', 'in:mild,moderate,severe'],
            'generic_name'          => ['required', 'in:purified_vero_cell,purified_chick_embryo,human_diploid'],
            'route'                 => ['required', 'in:intramuscular,intradermal'],
            'brand_name'            => ['required', 'in:verorab,speeda,rabiqur,abhayrab'],
            'dosage'                => ['required', 'in:0_1ml,0_5ml,1_0ml'],
            'anti_rabies_dose'      => ['required', 'in:day_0,day_3,day_7,day_14,day_28'],
            'anti_rabies_date'      => ['nullable', 'date'],
            'tetanus_status'        => ['required', 'in:valid,expired,unknown'],
            'tetanus_dose'          => ['required', 'in:dose1,dose2,dose3'],
            'tetanus_date'          => ['nullable', 'date'],
            'rabies_immunoglobulin' => ['required', 'in:erig,hrig,none'],
        ]);

        $validated['treatment_required'] = $request->input('treatment', []);
        unset($validated['treatment']);

        $validated['anti_rabies_date'] = $validated['anti_rabies_date'] ?? now()->toDateString();
        $validated['tetanus_date']     = $validated['tetanus_date']     ?? now()->toDateString();
        $validated['source']           = 'admin';
        $validated['branch_id']        = $this->branchId(); // ← attach to branch

        Patient::create($validated);

        return redirect()->route('admin.patients')->with('success', 'Patient added successfully.');
    }

    public function updatePatient(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'full_name'             => ['required', 'string', 'max:255'],
            'card_no'               => ['required', 'string', 'max:100'],
            'case_no'               => ['required', 'string', 'max:100', 'unique:patients,case_no,' . $patient->id],
            'contact'               => ['required', 'string', 'max:50'],
            'age'                   => ['required', 'integer', 'min:0', 'max:150'],
            'email'                 => ['nullable', 'email', 'max:255'],
            'gender'                => ['required', 'in:male,female,other'],
            'address'               => ['required', 'string', 'max:255'],
            'weight'                => ['required', 'numeric', 'min:0'],
            'cat_category'          => ['required', 'in:category_i,category_ii,category_iii'],
            'treatment'             => ['nullable', 'array'],
            'treatment.*'           => ['in:prprep,pep,booster,tet,erig,hrig'],
            'bite_type'             => ['nullable', 'in:scratch,bite,lick_broken_skin,open_wound_exposure'],
            'place_of_bite'         => ['required', 'in:hand,arm,leg,foot,face,neck,finger,multiple_sites'],
            'source'                => ['required', 'in:dog,cat,bat,rat,monkey,other_animal'],
            'severity'              => ['nullable', 'in:mild,moderate,severe'],
            'generic_name'          => ['required', 'in:purified_vero_cell,purified_chick_embryo,human_diploid'],
            'route'                 => ['required', 'in:intramuscular,intradermal'],
            'brand_name'            => ['required', 'in:verorab,speeda,rabiqur,abhayrab'],
            'dosage'                => ['required', 'in:0_1ml,0_5ml,1_0ml'],
            'anti_rabies_dose'      => ['required', 'in:day_0,day_3,day_7,day_14,day_28'],
            'tetanus_status'        => ['required', 'in:valid,expired,unknown'],
            'tetanus_dose'          => ['required', 'in:dose1,dose2,dose3'],
            'rabies_immunoglobulin' => ['required', 'in:erig,hrig,none'],
        ]);

        $validated['treatment_required'] = $request->input('treatment', []);
        unset($validated['treatment']);

        $patient->update($validated);

        return redirect()->route('admin.patients')->with('success', 'Patient updated successfully.');
    }

    public function destroyPatient(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patients')->with('success', 'Patient deleted successfully.');
    }

    public function updatePatientStatus(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,not_approved'],
        ]);

        $patient->update(['status' => $validated['status']]);

        AdminNotification::statusUpdated($patient->full_name, $validated['status'], $this->branchId());

        if ($validated['status'] === 'approved' && $patient->email) {
            try {
                Mail::to($patient->email)->send(new PatientApprovedMail($patient->full_name, null));
            } catch (\Exception $e) {
                logger()->error('Failed to send approval email for patient ' . $patient->id . ': ' . $e->getMessage());
            }
        }

        return response()->json(['success' => true, 'message' => 'Patient status updated successfully.']);
    }

    // ──────────────────────────────────────────────
    // Appointments
    // ──────────────────────────────────────────────

    public function appointments()
    {
        $appointments            = $this->appointmentQuery()->latest()->get();
        $totalAppointments       = $appointments->count();
        $approvedAppointments    = $appointments->where('status', 'approved')->count();
        $notApprovedAppointments = $appointments->where('status', '!=', 'approved')->count();
        $appointmentsPayload     = $appointments->map(fn (Appointment $a) => $this->buildAppointmentPayload($a))->values();

        return view('admin.appointments', compact(
            'appointments', 'totalAppointments',
            'approvedAppointments', 'notApprovedAppointments', 'appointmentsPayload'
        ));
    }

    public function appointmentsJson()
    {
        $appointments        = $this->appointmentQuery()->latest()->get();
        $appointmentsPayload = $appointments->map(fn (Appointment $a) => $this->buildAppointmentPayload($a))->values();
        $lastUpdated         = optional($appointments->first())->updated_at?->timestamp ?? null;

        return response()->json(['last_updated' => $lastUpdated, 'appointments' => $appointmentsPayload]);
    }

    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status'           => ['required', 'in:approved,not_approved'],
            'appointment_date' => ['nullable', 'date'],
            'appointment_time' => ['nullable', 'date_format:H:i'],
        ]);

        $appointment->status = $validated['status'];

        if (! empty($validated['appointment_date'])) {
            $appointment->appointment_date = $validated['appointment_date'];
        }
        if (! empty($validated['appointment_time'])) {
            $appointment->appointment_time = $validated['appointment_time'];
        }

        $appointment->save();

        $patient = $this->findRelatedPatient($appointment);
        if ($patient) {
            $patient->status = $validated['status'];
            $patient->save();
        }

        if ($validated['status'] === 'approved') {
            AdminNotification::appointmentConfirmed(
                $appointment->full_name,
                optional($appointment->appointment_date)->format('M d, Y'),
                $this->branchId()
            );
        } else {
            AdminNotification::statusUpdated($appointment->full_name, $validated['status'], $this->branchId());
        }

        $notificationEmail = $appointment->user?->email ?? $patient?->email;
        if ($validated['status'] === 'approved' && $notificationEmail) {
            try {
                Mail::to($notificationEmail)->send(new PatientApprovedMail($appointment->full_name, null));
            } catch (\Exception $e) {
                logger()->error('Failed to send approval email for appointment ' . $appointment->id . ': ' . $e->getMessage());
            }
        }

        if ($validated['status'] === 'approved') {
            ScheduledReminder::syncForAppointment($appointment, $notificationEmail);
        }

        return response()->json(['success' => true, 'message' => 'Appointment status updated successfully.']);
    }

    public function destroyAppointment(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['success' => true, 'message' => 'Appointment deleted successfully.']);
    }

    // ──────────────────────────────────────────────
    // Reports
    // ──────────────────────────────────────────────

    public function reports()
    {
        $patients       = $this->patientQuery()->latest()->get();
        $patientsPayload = $patients->map(function (Patient $patient) {
            return [
                'id'               => $patient->id,
                'patient'          => $patient->full_name,
                'email'            => $patient->email,
                'birthday'         => null,
                'birthday_raw'     => null,
                'age'              => $patient->age,
                'gender'           => ucfirst($patient->gender),
                'address'          => $patient->address,
                'contact'          => $patient->contact,
                'appointment_date' => null,
                'appointment_date_raw' => null,
                'status'           => $patient->status ?? null,
                'registered'       => optional($patient->created_at)->format('Y-m-d H:i:s'),
            ];
        })->values();

        return view('admin.reports', compact('patientsPayload'));
    }

    // ──────────────────────────────────────────────
    // Settings
    // ──────────────────────────────────────────────

    public function settings()
    {
        return view('admin.settings');
    }

    // ──────────────────────────────────────────────
    // Reminders
    // ──────────────────────────────────────────────

    public function reminders()
    {
        $this->appointmentQuery()
            ->with('user')
            ->where('status', 'approved')
            ->whereNotNull('appointment_date')
            ->get()
            ->each(function (Appointment $appointment): void {
                $patient = $this->findRelatedPatient($appointment);
                ScheduledReminder::syncForAppointment(
                    $appointment,
                    $appointment->user?->email ?? $patient?->email
                );
            });

        // Only show reminders tied to this branch's appointments
        $branchAppointmentIds = $this->appointmentQuery()->pluck('id');

        $reminders = ScheduledReminder::with('appointment')
            ->whereIn('appointment_id', $branchAppointmentIds)
            ->orderBy('reminder_date')
            ->orderBy('reminder_time')
            ->get();

        $upcomingCount = $reminders->where('reminder_date', '>=', today())->count();
        $sentCount     = $reminders->whereNotNull('sent_at')->count();
        $dueTodayCount = $reminders->filter(fn (ScheduledReminder $r) => $r->reminder_date?->isToday())->count();

        return view('admin.reminders', compact('reminders', 'upcomingCount', 'sentCount', 'dueTodayCount'));
    }

    public function sendReminder(ScheduledReminder $reminder)
    {
        if (empty($reminder->email)) {
            return back()->with('error', 'This reminder has no patient email address.');
        }

        try {
            Mail::to($reminder->email)->send(new ScheduledReminderMail($reminder));
            $reminder->update(['sent_at' => now()]);
        } catch (\Exception $e) {
            logger()->error('Failed to send scheduled reminder ' . $reminder->id . ': ' . $e->getMessage());
            return back()->with('error', 'Unable to send reminder email. Please check mail settings.');
        }

        return back()->with('success', 'Reminder email sent successfully.');
    }

    // ──────────────────────────────────────────────
    // Notifications
    // ──────────────────────────────────────────────

    public function notifications()
    {
        $notifications = AdminNotification::where('branch_id', $this->branchId())->latest()->paginate(30);
        $unreadCount   = AdminNotification::where('branch_id', $this->branchId())->unread()->count();

        return view('admin.notifications', compact('notifications', 'unreadCount'));
    }

    public function notificationsJson()
    {
        $notifications = AdminNotification::where('branch_id', $this->branchId())->latest()->take(20)->get();
        $unreadCount   = AdminNotification::where('branch_id', $this->branchId())->unread()->count();

        return response()->json(['unread_count' => $unreadCount, 'notifications' => $notifications]);
    }

    public function markNotificationRead(AdminNotification $notification)
    {
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        AdminNotification::where('branch_id', $this->branchId())->unread()->update(['is_read' => true, 'read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function destroyNotification(AdminNotification $notification)
    {
        $notification->delete();
        return response()->json(['success' => true]);
    }
}
