<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // ──────────────────────────────────────────────
    // Auth
    // ──────────────────────────────────────────────

    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        return view('superadmin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password) || ! $user->isSuperAdmin()) {
            return back()->withErrors(['email' => 'Invalid credentials or not a super admin.'])->withInput();
        }

        Auth::login($user);

        return redirect()->route('superadmin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login');
    }

    // ──────────────────────────────────────────────
    // Dashboard
    // ──────────────────────────────────────────────

    public function dashboard()
    {
        $branches      = Branch::withCount(['patients', 'appointments', 'admins'])->get();
        $totalBranches = $branches->count();

        // Aggregated stats
        $totalPatients      = Patient::count();
        $totalAppointments  = Appointment::count();
        $pendingPatients    = Patient::where('status', 'not_approved')->orWhereNull('status')->count();
        $approvedPatients   = Patient::where('status', 'approved')->count();
        $pendingAppointments = Appointment::where('status', 'not_approved')->orWhereNull('status')->count();
        $approvedAppointments = Appointment::where('status', 'approved')->count();
        $patientsToday      = Patient::whereDate('created_at', today())->count();
        $appointmentsToday  = Appointment::whereDate('appointment_date', today())->count();

        // Per-branch breakdown
        $branchStats = $branches->map(function (Branch $branch) {
            return [
                'id'           => $branch->id,
                'name'         => $branch->name,
                'location'     => $branch->location,
                'is_active'    => $branch->is_active,
                'patients'     => $branch->patients_count,
                'appointments' => $branch->appointments_count,
                'admins'       => $branch->admins_count,
                'pending'      => Patient::where('branch_id', $branch->id)
                    ->where(fn ($q) => $q->where('status', 'not_approved')->orWhereNull('status'))
                    ->count(),
            ];
        });

        return view('superadmin.dashboard', compact(
            'branches',
            'totalBranches',
            'totalPatients',
            'totalAppointments',
            'pendingPatients',
            'approvedPatients',
            'pendingAppointments',
            'approvedAppointments',
            'patientsToday',
            'appointmentsToday',
            'branchStats',
        ));
    }

    // ──────────────────────────────────────────────
    // Branch management
    // ──────────────────────────────────────────────

    public function branches()
    {
        $branches = Branch::withCount(['patients', 'appointments', 'admins'])->latest()->get();

        return view('superadmin.branches', compact('branches'));
    }

    public function storeBranch(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'address'  => 'nullable|string|max:255',
            'contact'  => 'nullable|string|max:50',
            'email'    => 'nullable|email|max:255',
        ]);

        Branch::create($validated);

        return redirect()->route('superadmin.branches')->with('success', 'Branch created successfully.');
    }

    public function updateBranch(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'location'  => 'required|string|max:255',
            'address'   => 'nullable|string|max:255',
            'contact'   => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $branch->update($validated);

        return redirect()->route('superadmin.branches')->with('success', 'Branch updated successfully.');
    }

    public function destroyBranch(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('superadmin.branches')->with('success', 'Branch deleted.');
    }

    // ──────────────────────────────────────────────
    // Branch admin management
    // ──────────────────────────────────────────────

    public function admins()
    {
        $admins   = User::where('is_admin', true)->where('is_super_admin', false)->with('branch')->latest()->get();
        $branches = Branch::where('is_active', true)->get();

        return view('superadmin.admins', compact('admins', 'branches'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'branch_id' => 'required|exists:branches,id',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'is_admin'  => true,
            'branch_id' => $validated['branch_id'],
        ]);

        return redirect()->route('superadmin.admins')->with('success', 'Branch admin created successfully.');
    }

    public function updateAdmin(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'branch_id' => 'required|exists:branches,id',
            'password'  => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'branch_id' => $validated['branch_id'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('superadmin.admins')->with('success', 'Branch admin updated successfully.');
    }

    public function destroyAdmin(User $user)
    {
        $user->delete();

        return redirect()->route('superadmin.admins')->with('success', 'Branch admin deleted.');
    }

    // ──────────────────────────────────────────────
    // View a single branch's data
    // ──────────────────────────────────────────────

    public function viewBranch(Branch $branch)
    {
        $patients     = Patient::where('branch_id', $branch->id)->latest()->get();
        $appointments = Appointment::where('branch_id', $branch->id)->latest()->get();
        $admins       = User::where('branch_id', $branch->id)->where('is_admin', true)->get();

        $totalPatients       = $patients->count();
        $pendingPatients     = $patients->filter(fn ($p) => ($p->status ?? 'not_approved') === 'not_approved')->count();
        $approvedPatients    = $patients->where('status', 'approved')->count();
        $totalAppointments   = $appointments->count();
        $pendingAppointments = $appointments->filter(fn ($a) => ($a->status ?? 'not_approved') === 'not_approved')->count();

        return view('superadmin.branch-view', compact(
            'branch',
            'patients',
            'appointments',
            'admins',
            'totalPatients',
            'pendingPatients',
            'approvedPatients',
            'totalAppointments',
            'pendingAppointments',
        ));
    }

    // ──────────────────────────────────────────────
    // Reports (aggregated + per branch)
    // ──────────────────────────────────────────────

    public function reports()
    {
        $branches    = Branch::with('patients')->get();
        $allPatients = Patient::with('branch')->latest()->get();

        $branchReports = $branches->map(function (Branch $branch) {
            return [
                'id'            => $branch->id,
                'name'          => $branch->name,
                'location'      => $branch->location,
                'total'         => $branch->patients->count(),
                'approved'      => $branch->patients->where('status', 'approved')->count(),
                'pending'       => $branch->patients->filter(fn ($p) => ($p->status ?? 'not_approved') !== 'approved')->count(),
                'severe'        => $branch->patients->where('severity', 'severe')->count(),
                'cat_i'         => $branch->patients->where('cat_category', 'category_i')->count(),
                'cat_ii'        => $branch->patients->where('cat_category', 'category_ii')->count(),
                'cat_iii'       => $branch->patients->where('cat_category', 'category_iii')->count(),
            ];
        });

        $monthlyReports = $this->monthlyPatientStatusReport();

        return view('superadmin.reports', compact('branches', 'allPatients', 'branchReports', 'monthlyReports'));
    }

    /**
     * Per-month patient status report.
     *
     * Statuses are derived from existing data, grouped by registration month:
     *  - Total      : cumulative patients registered up to the end of the month
     *  - Registered : patients registered during the month
     *  - Complete   : status is approved/completed/done
     *  - Ongoing    : status is not_approved/pending/empty (still in treatment)
     *  - Cancelled  : status is cancelled
     *  - Missing    : has an approved appointment whose date already passed
     */
    private function monthlyPatientStatusReport()
    {
        $months = collect();
        $earliest = Patient::query()->min('created_at');

        if ($earliest) {
            $cursor = Carbon::parse($earliest)->startOfMonth();
            $last = Carbon::now()->startOfMonth();

            while ($cursor->lte($last)) {
                $months->push($cursor->copy());
                $cursor->addMonth();
            }
        }

        $missedPatientIds = Appointment::query()
            ->where('status', 'approved')
            ->whereDate('appointment_date', '<', Carbon::today())
            ->pluck('patient_id')
            ->filter()
            ->values()
            ->all();

        $cumulative = 0;

        return $months->map(function (Carbon $month) use (&$cumulative, $missedPatientIds) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $patients = Patient::whereBetween('created_at', [$start, $end])
                ->get(['id', 'status']);

            $registered = $patients->count();
            $cumulative += $registered;

            $complete = $patients->filter(fn ($p) => in_array($p->status, ['approved', 'completed', 'done'], true))->count();
            $ongoing = $patients->filter(fn ($p) => ! in_array($p->status ?? '', ['approved', 'completed', 'done', 'cancelled'], true))->count();
            $cancelled = $patients->where('status', 'cancelled')->count();
            $missing = $patients->filter(fn ($p) => in_array($p->id, $missedPatientIds, true))->count();

            return [
                'label'      => $month->format('F Y'),
                'key'        => $month->format('Y-m'),
                'year'       => $month->format('Y'),
                'quarter'    => $month->quarter,
                'total'      => $cumulative,
                'registered' => $registered,
                'complete'   => $complete,
                'ongoing'    => $ongoing,
                'cancelled'  => $cancelled,
                'missing'    => $missing,
            ];
        })->values();
    }
}
