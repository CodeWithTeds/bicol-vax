@extends('layouts.user')

@section('title', 'View Records')

@section('content')
    <style>
        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            color: #2b8f90;
        }

        .records-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2b8f90;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #8fd8cc;
        }

        .section-description {
            color: #666;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .records-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .records-table thead {
            background: linear-gradient(135deg, #2b8f90 0%, #1f6566 100%);
            color: white;
        }

        .records-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .records-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.9rem;
        }

        .records-table tbody tr:hover {
            background-color: #f8fffe;
        }

        .records-table tbody tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-not_approved {
            background-color: #fff3cd;
            color: #856404;
        }

        .severity-severe {
            color: #dc3545;
            font-weight: 600;
        }

        .severity-moderate {
            color: #fd7e14;
            font-weight: 600;
        }

        .severity-mild {
            color: #28a745;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .empty-state p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .empty-state a {
            display: inline-block;
            background: #2b8f90;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .empty-state a:hover {
            background: #1f6566;
        }

        .appointments-section {
            margin-top: 2rem;
        }

        .appointment-card {
            background: white;
            padding: 1.25rem;
            border-radius: 10px;
            margin-bottom: 0.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #2b8f90;
        }

        .appointment-info h4 {
            margin: 0 0 0.25rem 0;
            color: #333;
        }

        .appointment-info p {
            margin: 0;
            color: #666;
            font-size: 0.85rem;
        }

        .appointment-date {
            text-align: right;
            font-weight: 600;
            color: #2b8f90;
        }

        @media (max-width: 768px) {
            .records-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>

    <div class="page-header">
        <h1>📋 View Records</h1>
        <p>View your complete vaccination history and appointment details. All past and upcoming records associated with your profile are displayed below.</p>
    </div>

    <div class="records-container">
        {{-- Vaccination Records Section --}}
        <div>
            <h2 class="section-title">Vaccination Records</h2>
            <p class="section-description">Your anti-rabies treatment and vaccination history.</p>

            @if($patients->count() > 0)
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>Card No.</th>
                            <th>Case No.</th>
                            <th>Category</th>
                            <th>Bite Type</th>
                            <th>Severity</th>
                            <th>Vaccine</th>
                            <th>Dose</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->card_no }}</td>
                                <td>{{ $patient->case_no }}</td>
                                <td>{{ $patient->cat_category }}</td>
                                <td>{{ $patient->bite_type ?? 'N/A' }}</td>
                                <td>
                                    <span class="severity-{{ strtolower($patient->severity ?? 'mild') }}">
                                        {{ ucfirst($patient->severity ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>{{ $patient->brand_name }}</td>
                                <td>{{ $patient->anti_rabies_dose }} ({{ $patient->dosage }})</td>
                                <td>{{ \Carbon\Carbon::parse($patient->anti_rabies_date)->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge status-{{ $patient->status }}">
                                        {{ $patient->status === 'approved' ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <p>📭 No vaccination records found.</p>
                    <a href="{{ route('user.booking') }}">Book an Appointment</a>
                </div>
            @endif
        </div>

        {{-- Appointments Section --}}
        <div class="appointments-section">
            <h2 class="section-title">Appointment History</h2>
            <p class="section-description">Your scheduled and past appointments.</p>

            @if($appointments->count() > 0)
                @foreach($appointments as $appointment)
                    <div class="appointment-card">
                        <div class="appointment-info">
                            <h4>{{ $appointment->full_name }}</h4>
                            <p>{{ ucfirst($appointment->gender) }} &bull; Age: {{ $appointment->age }} &bull; {{ $appointment->address }}</p>
                        </div>
                        <div class="appointment-date">
                            <div>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</div>
                            <span class="status-badge status-{{ $appointment->status }}">
                                {{ $appointment->status === 'approved' ? 'Approved' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <p>📭 No appointments found.</p>
                    <a href="{{ route('user.booking') }}">Book an Appointment</a>
                </div>
            @endif
        </div>
    </div>
@endsection
