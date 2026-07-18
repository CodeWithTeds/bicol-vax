@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
    <style>
        .page-header {
            margin-bottom: 2rem;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: linear-gradient(135deg, #8fd8cc 0%, #a8e6e1 100%);
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .records-section {
            background: linear-gradient(135deg, #5fa9ab 0%, #6eb8bb 100%);
            padding: 2rem;
            border-radius: 12px;
            color: white;
        }

        .records-section h2 {
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .records-section p {
            opacity: 0.9;
            margin-bottom: 1.5rem;
        }

        .record-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #50c878;
        }

        .record-item:last-child {
            margin-bottom: 0;
        }

        .record-content h3 {
            margin: 0;
            font-size: 1rem;
            margin-bottom: 0.3rem;
        }

        .record-content p {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .record-date {
            text-align: right;
            font-weight: 600;
        }

        .record-date p {
            margin: 0;
            font-size: 0.85rem;
        }
    </style>

    <div class="page-header">
        <h1>Quick Actions</h1>
        <p>Manage your vaccination appointment and records</p>
    </div>

    <div class="quick-actions">
        <a href="{{ route('user.booking') }}" class="action-card">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📅</div>
            Booking Appointment
        </a>
        <a href="{{ route('user.records') }}" class="action-card">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">📋</div>
            View Records
        </a>
        <a href="#" class="action-card">
            <div style="font-size: 1.5rem; margin-bottom: 0.5rem;">🔔</div>
            Scheduled Reminders
        </a>
    </div>

    <div class="records-section">
        <h2>Recent Vaccination Records</h2>
        <p>Your latest vaccination history</p>

        @forelse($patients as $patient)
            <div class="record-item">
                <div class="record-content">
                    <h3>{{ $patient->brand_name }} ({{ $patient->generic_name }})</h3>
                    <p>{{ $patient->anti_rabies_dose }} &bull; {{ $patient->route }} &bull; {{ $patient->dosage }}</p>
                </div>
                <div class="record-date">
                    <p>{{ ucfirst(str_replace('_', ' ', $patient->status)) }}</p>
                    <p style="font-size: 1.1rem;">{{ \Carbon\Carbon::parse($patient->anti_rabies_date)->format('m/d/Y') }}</p>
                </div>
            </div>
        @empty
            <div class="record-item">
                <div class="record-content">
                    <h3>No records found</h3>
                    <p>You don't have any vaccination records yet.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
