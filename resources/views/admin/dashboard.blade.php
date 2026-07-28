@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <style>
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .dash-stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #2b8f90;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .dash-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .dash-stat-card.accent-green { border-left-color: #50c878; }
        .dash-stat-card.accent-orange { border-left-color: #ff9800; }
        .dash-stat-card.accent-blue { border-left-color: #3b82f6; }
        .dash-stat-card.accent-red { border-left-color: #ef4444; }
        .dash-stat-card.accent-purple { border-left-color: #8b5cf6; }

        .dash-stat-card .stat-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.4rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .dash-stat-card .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2b8f90;
            line-height: 1.2;
        }

        .dash-stat-card.accent-green .stat-number { color: #50c878; }
        .dash-stat-card.accent-orange .stat-number { color: #ff9800; }
        .dash-stat-card.accent-blue .stat-number { color: #3b82f6; }
        .dash-stat-card.accent-red .stat-number { color: #ef4444; }
        .dash-stat-card.accent-purple .stat-number { color: #8b5cf6; }

        .dash-stat-card .stat-desc {
            font-size: 0.8rem;
            color: #999;
            margin-top: 0.3rem;
        }

        .dashboard-sections {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 900px) {
            .dashboard-sections { grid-template-columns: 1fr; }
        }

        .dash-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
        }

        .dash-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #eef2f5;
        }

        .dash-card-header h3 {
            font-size: 1.1rem;
            color: #2b8f90;
            font-weight: 700;
        }

        .status-breakdown {
            display: grid;
            gap: 0.75rem;
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 1rem;
            border-radius: 8px;
            background: #f8fafb;
            border: 1px solid #e8ecef;
        }

        .status-row .status-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #444;
            font-weight: 500;
        }

        .status-row .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .status-dot.pending { background-color: #ff9800; }
        .status-dot.approved { background-color: #50c878; }
        .status-dot.completed { background-color: #3b82f6; }
        .status-dot.severe { background-color: #ef4444; }

        .status-row .status-count {
            font-size: 1.1rem;
            font-weight: 700;
            color: #222;
        }

        .progress-bar-container {
            margin-top: 1rem;
        }

        .progress-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.4rem;
        }

        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: #e8ecef;
            overflow: hidden;
            display: flex;
        }

        .progress-segment {
            height: 100%;
            transition: width 0.4s ease;
        }

        .progress-segment.approved { background: #50c878; }
        .progress-segment.pending { background: #ff9800; }
        .progress-segment.completed { background: #3b82f6; }

        .recent-list {
            display: grid;
            gap: 0.6rem;
        }

        .recent-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            background: #f8fafb;
            border: 1px solid #e8ecef;
            transition: background 0.2s;
        }

        .recent-item:hover {
            background: #eef7f7;
        }

        .recent-item .item-name {
            font-weight: 600;
            color: #222;
            font-size: 0.9rem;
        }

        .recent-item .item-detail {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.15rem;
        }

        .recent-item .item-time {
            font-size: 0.8rem;
            color: #2b8f90;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-sm {
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-sm.approved { background: #d4edda; color: #155724; }
        .badge-sm.pending { background: #fff3cd; color: #856404; }
        .badge-sm.completed { background: #d1ecf1; color: #0c5460; }

        .full-width-section {
            grid-column: 1 / -1;
        }
    </style>

    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Overview of system activity – patients, appointments, and key statistics at a glance.</p>
    </div>

    {{-- Key Stats Cards --}}
    <div class="dashboard-stats">
        <div class="dash-stat-card">
            <div class="stat-label">Total Patients</div>
            <div class="stat-number">{{ $totalPatients }}</div>
            <div class="stat-desc">All registered patient records</div>
        </div>

        <div class="dash-stat-card accent-blue">
            <div class="stat-label">Total Appointments</div>
            <div class="stat-number">{{ $totalAppointments }}</div>
            <div class="stat-desc">All booking requests</div>
        </div>

        <div class="dash-stat-card accent-green">
            <div class="stat-label">Registered Today</div>
            <div class="stat-number">{{ $patientsToday }}</div>
            <div class="stat-desc">New patients added today</div>
        </div>

        <div class="dash-stat-card accent-orange">
            <div class="stat-label">Appointments Today</div>
            <div class="stat-number">{{ $appointmentsToday }}</div>
            <div class="stat-desc">Scheduled for today</div>
        </div>

        <div class="dash-stat-card accent-red">
            <div class="stat-label">Severe Cases</div>
            <div class="stat-number">{{ $severeCases }}</div>
            <div class="stat-desc">Patients tagged as severe</div>
        </div>
    </div>

    {{-- Status Breakdown Sections --}}
    <div class="dashboard-sections">
        {{-- Patient Status Breakdown --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>Patient Status</h3>
                <a href="{{ route('admin.patients') }}" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.85rem;">View All</a>
            </div>
            <div class="status-breakdown">
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot pending"></span>
                        Pending
                    </div>
                    <span class="status-count">{{ $pendingPatients }}</span>
                </div>
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot approved"></span>
                        Approved
                    </div>
                    <span class="status-count">{{ $approvedPatients }}</span>
                </div>
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot severe"></span>
                        Severe Cases
                    </div>
                    <span class="status-count">{{ $severeCases }}</span>
                </div>
            </div>
            @if($totalPatients > 0)
                <div class="progress-bar-container">
                    <div class="progress-bar-label">
                        <span>Approved: {{ $totalPatients > 0 ? round(($approvedPatients / $totalPatients) * 100) : 0 }}%</span>
                        <span>Pending: {{ $totalPatients > 0 ? round(($pendingPatients / $totalPatients) * 100) : 0 }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-segment approved" style="width: {{ $totalPatients > 0 ? ($approvedPatients / $totalPatients) * 100 : 0 }}%"></div>
                        <div class="progress-segment pending" style="width: {{ $totalPatients > 0 ? ($pendingPatients / $totalPatients) * 100 : 0 }}%"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Appointment Status Breakdown --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>Appointment Status</h3>
                <a href="{{ route('admin.appointments') }}" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.85rem;">View All</a>
            </div>
            <div class="status-breakdown">
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot pending"></span>
                        Pending
                    </div>
                    <span class="status-count">{{ $pendingAppointments }}</span>
                </div>
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot approved"></span>
                        Approved
                    </div>
                    <span class="status-count">{{ $approvedAppointments }}</span>
                </div>
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot completed"></span>
                        Completed
                    </div>
                    <span class="status-count">{{ $completedAppointments }}</span>
                </div>
            </div>
            @if($totalAppointments > 0)
                <div class="progress-bar-container">
                    <div class="progress-bar-label">
                        <span>Approved: {{ round(($approvedAppointments / $totalAppointments) * 100) }}%</span>
                        <span>Completed: {{ round(($completedAppointments / $totalAppointments) * 100) }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-segment approved" style="width: {{ ($approvedAppointments / $totalAppointments) * 100 }}%"></div>
                        <div class="progress-segment completed" style="width: {{ ($completedAppointments / $totalAppointments) * 100 }}%"></div>
                        <div class="progress-segment pending" style="width: {{ ($pendingAppointments / $totalAppointments) * 100 }}%"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Exposure Category Summary --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>Exposure Categories</h3>
            </div>
            <div class="status-breakdown">
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot" style="background-color: #50c878;"></span>
                        Category I
                    </div>
                    <span class="status-count">{{ $categoryICases }}</span>
                </div>
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot" style="background-color: #ff9800;"></span>
                        Category II
                    </div>
                    <span class="status-count">{{ $categoryIICases }}</span>
                </div>
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot" style="background-color: #ef4444;"></span>
                        Category III
                    </div>
                    <span class="status-count">{{ $categoryIIICases }}</span>
                </div>
            </div>
        </div>

        {{-- Today's Activity --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>Today's Activity</h3>
            </div>
            <div class="status-breakdown">
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot" style="background-color: #2b8f90;"></span>
                        New Patients
                    </div>
                    <span class="status-count">{{ $patientsToday }}</span>
                </div>
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot" style="background-color: #3b82f6;"></span>
                        Scheduled Appointments
                    </div>
                    <span class="status-count">{{ $appointmentsToday }}</span>
                </div>
                <div class="status-row">
                    <div class="status-label">
                        <span class="status-dot" style="background-color: #8b5cf6;"></span>
                        Total Records
                    </div>
                    <span class="status-count">{{ $totalPatients + $totalAppointments }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity Tables --}}
    <div class="dashboard-sections">
        {{-- Recent Patients --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>Recent Patients</h3>
                <a href="{{ route('admin.patients') }}" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.85rem;">Open List</a>
            </div>
            <div class="recent-list">
                @forelse ($recentPatients as $patient)
                    <div class="recent-item">
                        <div>
                            <div class="item-name">{{ $patient->full_name }}</div>
                            <div class="item-detail">Case #{{ $patient->case_no }} · {{ $patient->contact }}</div>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge-sm {{ $patient->status === 'approved' ? 'approved' : 'pending' }}">
                                {{ $patient->status === 'approved' ? 'Approved' : 'Pending' }}
                            </span>
                            <div class="item-time" style="margin-top: 0.25rem;">{{ optional($patient->created_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p style="color: #999; text-align: center; padding: 1rem;">No patients recorded yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Appointments --}}
        <div class="dash-card">
            <div class="dash-card-header">
                <h3>Recent Appointments</h3>
                <a href="{{ route('admin.appointments') }}" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.85rem;">Open List</a>
            </div>
            <div class="recent-list">
                @forelse ($recentAppointments as $appointment)
                    <div class="recent-item">
                        <div>
                            <div class="item-name">{{ $appointment->full_name }}</div>
                            <div class="item-detail">
                                {{ optional($appointment->appointment_date)->format('M d, Y') ?? 'No date set' }}
                                @if($appointment->appointment_time)
                                    · {{ $appointment->appointment_time }}
                                @endif
                            </div>
                        </div>
                        <div style="text-align: right;">
                            @php
                                $aptStatus = $appointment->status;
                                $badgeClass = 'pending';
                                $badgeText = 'Pending';
                                if ($aptStatus === 'approved') { $badgeClass = 'approved'; $badgeText = 'Approved'; }
                                elseif (in_array($aptStatus, ['completed', 'done'])) { $badgeClass = 'completed'; $badgeText = 'Completed'; }
                            @endphp
                            <span class="badge-sm {{ $badgeClass }}">{{ $badgeText }}</span>
                            <div class="item-time" style="margin-top: 0.25rem;">{{ optional($appointment->created_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p style="color: #999; text-align: center; padding: 1rem;">No appointments yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Overview Table --}}
    <div class="dash-card" style="margin-bottom: 2rem;">
        <div class="dash-card-header">
            <h3>Recent Patient Registrations</h3>
            <a href="{{ route('admin.patients') }}" class="btn btn-primary" style="padding: 0.4rem 1rem; font-size: 0.85rem;">View All Patients</a>
        </div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Case No.</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Category</th>
                        <th>Dose</th>
                        <th>Status</th>
                        <th>Added</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentPatients as $patient)
                        <tr>
                            <td>{{ $patient->case_no }}</td>
                            <td>{{ $patient->full_name }}</td>
                            <td>{{ $patient->contact }}</td>
                            <td>{{ strtoupper(str_replace('category_', 'CAT ', $patient->cat_category)) }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $patient->anti_rabies_dose)) }}</td>
                            <td>
                                <span class="badge-sm {{ $patient->status === 'approved' ? 'approved' : 'pending' }}">
                                    {{ $patient->status === 'approved' ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td>{{ optional($patient->created_at)->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #999; padding: 1.5rem;">No patients recorded yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
