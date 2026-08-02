@extends('layouts.admin')

@section('title', 'Scheduled Reminders')

@section('content')
    <style>
        .reminders-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .reminders-header h1 {
            margin: 0 0 0.35rem;
            color: #243b3b;
            font-size: 1.8rem;
        }

        .reminders-header p {
            margin: 0;
            color: #527272;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            background: #e8f7f6;
            border: 1px solid #c7dede;
            border-radius: 8px;
            padding: 1rem;
        }

        .summary-card span {
            display: block;
            color: #527272;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .summary-card strong {
            display: block;
            margin-top: 0.4rem;
            color: #243b3b;
            font-size: 1.7rem;
        }

        .reminders-table-wrap {
            background: #fff;
            border: 1px solid #dbeaea;
            border-radius: 8px;
            overflow: hidden;
        }

        .reminders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reminders-table th,
        .reminders-table td {
            padding: 0.9rem;
            border-bottom: 1px solid #edf5f5;
            text-align: left;
            vertical-align: middle;
        }

        .reminders-table th {
            background: #f4fbfb;
            color: #243b3b;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .status-pill {
            display: inline-block;
            padding: 0.3rem 0.55rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .status-sent {
            background: #e8f7ee;
            color: #1f6b38;
        }

        .status-pending {
            background: #fff7df;
            color: #8a6100;
        }

        .btn-send {
            border: 0;
            border-radius: 6px;
            background: #2b8f90;
            color: #fff;
            padding: 0.45rem 0.75rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-send:disabled {
            background: #b7caca;
            cursor: not-allowed;
        }

        .alert {
            margin-bottom: 1rem;
            padding: 0.85rem 1rem;
            border-radius: 6px;
        }

        .alert-success {
            background: #e8f7ee;
            color: #1f6b38;
            border: 1px solid #bfe8cb;
        }

        .alert-error {
            background: #fdecec;
            color: #9b1c1c;
            border: 1px solid #f5b5b5;
        }
    </style>

    <div class="reminders-header">
        <div>
            <h1>Scheduled Reminders</h1>
            <p>Day 0, Day 3, and Day 7 vaccination reminders for approved appointments.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="summary-grid">
        <div class="summary-card">
            <span>Upcoming</span>
            <strong>{{ $upcomingCount }}</strong>
        </div>
        <div class="summary-card">
            <span>Due Today</span>
            <strong>{{ $dueTodayCount }}</strong>
        </div>
        <div class="summary-card">
            <span>Email Sent</span>
            <strong>{{ $sentCount }}</strong>
        </div>
    </div>

    <div class="reminders-table-wrap">
        <table class="reminders-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Email</th>
                    <th>Dose</th>
                    <th>Reminder Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reminders as $reminder)
                    <tr>
                        <td>{{ $reminder->patient_name }}</td>
                        <td>{{ $reminder->email ?? 'No email' }}</td>
                        <td>{{ $reminder->dose_label }}</td>
                        <td>
                            {{ optional($reminder->reminder_date)->format('M d, Y') }}
                            @if($reminder->reminder_time)
                                <br><span style="color:#668181;font-size:0.82rem;">{{ $reminder->reminder_time }}</span>
                            @endif
                        </td>
                        <td>
                            @if($reminder->sent_at)
                                <span class="status-pill status-sent">Sent</span>
                            @else
                                <span class="status-pill status-pending">Pending</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.reminders.send', $reminder) }}">
                                @csrf
                                <button class="btn-send" type="submit" @disabled(empty($reminder->email))>
                                    Send Email
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:2rem;text-align:center;color:#668181;">
                            No reminders yet. Approve an appointment with a schedule date to create Day 0, Day 3, and Day 7 reminders.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
