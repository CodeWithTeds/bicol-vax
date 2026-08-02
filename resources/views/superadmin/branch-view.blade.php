@extends('layouts.superadmin')

@section('title', $branch->name)

@section('content')
<div class="page-header">
    <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
        <a href="{{ route('superadmin.branches') }}" class="btn btn-outline" style="padding:0.4rem 0.9rem; font-size:0.85rem;">← Back</a>
        <div>
            <h1>{{ $branch->name }}</h1>
            <p>{{ $branch->location }}{{ $branch->address ? ' · ' . $branch->address : '' }}</p>
        </div>
        <span class="badge {{ $branch->is_active ? 'badge-active' : 'badge-inactive' }}">
            {{ $branch->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>
</div>

{{-- Stats --}}
<div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));">
    <div class="stat-card accent-blue">
        <div class="stat-label">Total Patients</div>
        <div class="stat-number">{{ $totalPatients }}</div>
    </div>
    <div class="stat-card accent-green">
        <div class="stat-label">Approved</div>
        <div class="stat-number">{{ $approvedPatients }}</div>
    </div>
    <div class="stat-card accent-orange">
        <div class="stat-label">Pending</div>
        <div class="stat-number">{{ $pendingPatients }}</div>
    </div>
    <div class="stat-card accent-purple">
        <div class="stat-label">Appointments</div>
        <div class="stat-number">{{ $totalAppointments }}</div>
    </div>
    <div class="stat-card accent-red">
        <div class="stat-label">Pending Appts</div>
        <div class="stat-number">{{ $pendingAppointments }}</div>
    </div>
</div>

{{-- Admins --}}
<div class="content-card" style="margin-bottom:1.5rem;">
    <h2 style="color:#2b8f90; font-size:1.1rem; font-weight:700; margin-bottom:1rem;">Branch Admins</h2>
    @if($admins->isEmpty())
        <p style="color:#999;">No admins assigned. <a href="{{ route('superadmin.admins') }}" style="color:#2b8f90;">Assign one →</a></p>
    @else
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            @foreach($admins as $admin)
                <div style="background:#f6fbfb; border:1px solid #e0eeef; border-radius:8px; padding:0.75rem 1.25rem;">
                    <div style="font-weight:600; color:#1a3a3a;">{{ $admin->name }}</div>
                    <div style="font-size:0.82rem; color:#666;">{{ $admin->email }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Patients Table --}}
<div class="content-card" style="margin-bottom:1.5rem;">
    <h2 style="color:#2b8f90; font-size:1.1rem; font-weight:700; margin-bottom:1rem;">Patients</h2>
    @if($patients->isEmpty())
        <p style="color:#999; text-align:center; padding:1.5rem;">No patients registered under this branch.</p>
    @else
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Case No.</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Contact</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $p)
                        <tr>
                            <td>{{ $p->case_no }}</td>
                            <td style="font-weight:600;">{{ $p->full_name }}</td>
                            <td>{{ $p->age }}</td>
                            <td>{{ $p->contact }}</td>
                            <td>{{ strtoupper(str_replace('category_', 'CAT ', $p->cat_category ?? '')) }}</td>
                            <td>
                                <span class="badge {{ $p->status === 'approved' ? 'badge-approved' : 'badge-pending' }}">
                                    {{ $p->status === 'approved' ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td style="color:#666;">{{ $p->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Appointments Table --}}
<div class="content-card">
    <h2 style="color:#2b8f90; font-size:1.1rem; font-weight:700; margin-bottom:1rem;">Appointments</h2>
    @if($appointments->isEmpty())
        <p style="color:#999; text-align:center; padding:1.5rem;">No appointments for this branch.</p>
    @else
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Appointment Date</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $a)
                        <tr>
                            <td style="font-weight:600;">{{ $a->full_name }}</td>
                            <td>{{ optional($a->appointment_date)->format('M d, Y') ?? '—' }}</td>
                            <td>{{ $a->contact }}</td>
                            <td>
                                <span class="badge {{ $a->status === 'approved' ? 'badge-approved' : 'badge-pending' }}">
                                    {{ $a->status === 'approved' ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td style="color:#666;">{{ $a->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
