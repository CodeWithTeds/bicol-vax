@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Live clinic overview based on the latest patient records.</p>
    </div>

    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-card-title">Total Patients</div>
            <div class="stat-card-value">{{ $totalPatients }}</div>
            <div class="stat-card-change">All registered patient records</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">Registered Today</div>
            <div class="stat-card-value">{{ $patientsToday }}</div>
            <div class="stat-card-change">New records added today</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">Severe Cases</div>
            <div class="stat-card-value">{{ $severeCases }}</div>
            <div class="stat-card-change">Patients tagged as severe</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-title">Category III</div>
            <div class="stat-card-value">{{ $categoryIIICases }}</div>
            <div class="stat-card-change">Highest exposure risk</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="content-card">
            <div class="content-header">
                <h2>Exposure Summary</h2>
            </div>
            <div style="display: grid; gap: 0.85rem;">
                <div style="display: flex; justify-content: space-between; color: #555;"><span>Category I</span><strong>{{ $categoryICases }}</strong></div>
                <div style="display: flex; justify-content: space-between; color: #555;"><span>Category II</span><strong>{{ $categoryIICases }}</strong></div>
                <div style="display: flex; justify-content: space-between; color: #555;"><span>Category III</span><strong>{{ $categoryIIICases }}</strong></div>
            </div>
        </div>
    </div>

    <div class="content-card" style="margin-bottom: 2rem;">
        <div class="content-header">
            <h2>Recent Patient Registrations</h2>
            <a href="{{ route('admin.patients') }}" class="btn btn-primary">View All Patients</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Case No.</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>CAT</th>
                    <th>Dose</th>
                    <th>Added</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentPatients as $patient)
                    <tr>
                        <td>{{ $patient->case_no }}</td>
                        <td>{{ $patient->full_name }}</td>
                        <td>{{ $patient->contact }}</td>
                        <td>{{ strtoupper(str_replace('category_', 'CATEGORY ', $patient->cat_category)) }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $patient->anti_rabies_dose)) }}</td>
                        <td>{{ optional($patient->created_at)->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">No patients recorded yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="content-card">
            <div class="content-header">
                <h2>Today's Overview</h2>
            </div>
            <div style="padding: 1rem 0; display: grid; gap: 0.85rem;">
                <div style="display: flex; justify-content: space-between; color: #555;"><span>Patients registered today</span><strong>{{ $patientsToday }}</strong></div>
                <div style="display: flex; justify-content: space-between; color: #555;"><span>Severe cases</span><strong>{{ $severeCases }}</strong></div>
                <div style="display: flex; justify-content: space-between; color: #555;"><span>Total patients</span><strong>{{ $totalPatients }}</strong></div>
            </div>
        </div>

        <div class="content-card">
            <div class="content-header">
                <h2>Latest Patients</h2>
                <a href="{{ route('admin.patients') }}" class="btn btn-primary">Open List</a>
            </div>
            <div style="display: grid; gap: 0.75rem; padding-top: 0.5rem;">
                @forelse ($recentPatients as $patient)
                    <div style="padding: 0.9rem 1rem; border: 1px solid #e8ecef; border-radius: 10px; background: #fafafa; display: flex; justify-content: space-between; gap: 1rem;">
                        <div>
                            <div style="font-weight: 700; color: #222;">{{ $patient->full_name }}</div>
                            <div style="font-size: 0.9rem; color: #666;">Case No. {{ $patient->case_no }} · {{ $patient->contact }}</div>
                        </div>
                        <div style="text-align: right; font-size: 0.9rem; color: #2b8f90; font-weight: 700;">
                            {{ optional($patient->created_at)->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <p style="color: #999; margin: 0;">No recent patients yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
