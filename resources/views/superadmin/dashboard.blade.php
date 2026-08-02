@extends('layouts.superadmin')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Aggregated overview across all {{ $totalBranches }} BicolVax branches.</p>
</div>

<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-label">Total Branches</div>
        <div class="stat-number">{{ $totalBranches }}</div>
    </div>
    <div class="stat-card accent-blue">
        <div class="stat-label">Total Patients</div>
        <div class="stat-number">{{ $totalPatients }}</div>
    </div>
    <div class="stat-card accent-purple">
        <div class="stat-label">Total Appointments</div>
        <div class="stat-number">{{ $totalAppointments }}</div>
    </div>
    <div class="stat-card accent-green">
        <div class="stat-label">Approved Patients</div>
        <div class="stat-number">{{ $approvedPatients }}</div>
    </div>
    <div class="stat-card accent-orange">
        <div class="stat-label">Pending Patients</div>
        <div class="stat-number">{{ $pendingPatients }}</div>
    </div>
    <div class="stat-card accent-green">
        <div class="stat-label">Registered Today</div>
        <div class="stat-number">{{ $patientsToday }}</div>
    </div>
    <div class="stat-card accent-blue">
        <div class="stat-label">Appointments Today</div>
        <div class="stat-number">{{ $appointmentsToday }}</div>
    </div>
</div>

<div class="content-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <h2 style="color:#2b8f90; font-size:1.3rem; font-weight:700;">Branch Overview</h2>
        <a href="{{ route('superadmin.branches') }}" class="btn btn-secondary" style="padding:0.4rem 1rem; font-size:0.85rem;">Manage Branches</a>
    </div>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Branch</th>
                    <th>Location</th>
                    <th>Patients</th>
                    <th>Appointments</th>
                    <th>Pending</th>
                    <th>Admins</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branchStats as $b)
                    <tr>
                        <td style="font-weight:600;">{{ $b['name'] }}</td>
                        <td style="color:#666;">{{ $b['location'] }}</td>
                        <td><strong style="color:#2b8f90;">{{ $b['patients'] }}</strong></td>
                        <td><strong style="color:#3b82f6;">{{ $b['appointments'] }}</strong></td>
                        <td>
                            @if($b['pending'] > 0)
                                <span class="badge badge-pending">{{ $b['pending'] }} pending</span>
                            @else
                                <span style="color:#999;">—</span>
                            @endif
                        </td>
                        <td>{{ $b['admins'] }}</td>
                        <td>
                            <span class="badge {{ $b['is_active'] ? 'badge-active' : 'badge-inactive' }}">
                                {{ $b['is_active'] ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('superadmin.branches.view', $b['id']) }}" class="btn btn-secondary" style="padding:0.35rem 0.7rem; font-size:0.8rem;">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:#999; padding:2rem;">
                            No branches yet. <a href="{{ route('superadmin.branches') }}" style="color:#2b8f90;">Create one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
