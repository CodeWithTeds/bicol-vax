@extends('layouts.user')

@section('title', 'My Appointments')

@section('content')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            color: #333;
        }

        .page-header p {
            color: #666;
            margin-top: 0.5rem;
        }

        .btn-schedule {
            background: #2b8f90;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s;
        }

        .btn-schedule:hover {
            background: #1f6566;
        }

        .appointments-container {
            background: linear-gradient(135deg, #5fa9ab 0%, #6eb8bb 100%);
            padding: 2rem;
            border-radius: 12px;
            color: white;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            text-align: left;
            overflow-x: auto;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .empty-icon {
            font-size: 3rem;
            opacity: 0.6;
        }

        .empty-state h2 {
            font-size: 1.2rem;
            margin: 0;
        }

        .empty-state p {
            margin: 0;
            opacity: 0.9;
        }

        .btn-book {
            background: #2b8f90;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 1rem;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-book:hover {
            background: #1f6566;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            overflow: hidden;
        }

        .appointments-table thead th {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
            text-align: left;
            padding: 1rem;
            font-size: 0.9rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        .appointments-table tbody td {
            padding: 1rem;
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            vertical-align: top;
        }

        .appointments-table tbody tr:last-child td {
            border-bottom: none;
        }

        .appointments-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .appointment-name {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
        }

        .appointment-meta {
            margin: 0;
            font-size: 0.9rem;
            opacity: 0.9;
            line-height: 1.55;
        }

        .appointment-status {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: rgba(80, 200, 120, 0.18);
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .appointment-date {
            white-space: nowrap;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .appointments-container {
                padding: 1rem;
            }
        }
    </style>

    <div class="page-header">
        <div>
            <h1>📅 My Appointment</h1>
            <p>View and manage your vaccination appointment</p>
        </div>
        <a href="{{ route('user.booking') }}" class="btn-schedule">+ Schedule Appointment</a>
    </div>

    <div class="appointments-container">
        @if(isset($appointments) && $appointments->count())
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Appointment Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $apt)
                        <tr>
                            <td>
                                <div class="appointment-name">{{ $apt->full_name }}</div>
                            </td>
                            <td>
                                <div class="appointment-meta">{{ $apt->contact }}</div>
                            </td>
                            <td>
                                <div class="appointment-meta">{{ $apt->address }}</div>
                            </td>
                            <td>
                                <div class="appointment-date">{{ optional($apt->appointment_date)->format('M d, Y') ?? 'TBD' }}</div>
                            </td>
                            <td>
                                <div class="appointment-meta">{{ $apt->appointment_time ?? 'TBD' }}</div>
                            </td>
                            <td>
                                <span class="appointment-status">{{ ucfirst($apt->status) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h2>Your don't have any appointment yet.</h2>
                <a href="{{ route('user.booking') }}" class="btn-book">+ Book New Appointment</a>
            </div>
        @endif
    </div>
@endsection
