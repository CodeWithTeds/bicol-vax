@extends('layouts.user')

@section('title', 'Reminders')

@section('content')
    <style>
        .reminders-page {
            max-width: 980px;
            margin: 0 auto;
        }

        .reminders-page h1 {
            margin-bottom: 0.35rem;
            color: #243b3b;
            font-size: 1.8rem;
        }

        .reminders-page .intro {
            margin-bottom: 1.5rem;
            color: #527272;
        }

        .reminder-list {
            display: grid;
            gap: 1rem;
        }

        .reminder-card {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1rem;
            align-items: center;
            background: #fff;
            border: 1px solid #dbeaea;
            border-left: 5px solid #2b8f90;
            border-radius: 8px;
            padding: 1rem;
        }

        .reminder-date {
            min-width: 78px;
            text-align: center;
            color: #243b3b;
        }

        .reminder-date strong {
            display: block;
            font-size: 1.6rem;
            line-height: 1;
        }

        .reminder-date span,
        .reminder-meta {
            color: #668181;
            font-size: 0.82rem;
        }

        .reminder-main h2 {
            margin: 0 0 0.25rem;
            color: #243b3b;
            font-size: 1.05rem;
        }

        .reminder-status {
            padding: 0.32rem 0.62rem;
            border-radius: 999px;
            background: #fff7df;
            color: #8a6100;
            font-size: 0.75rem;
            font-weight: 800;
        }

        .reminder-status.today {
            background: #fdecec;
            color: #9b1c1c;
        }

        .reminder-status.done {
            background: #e8f7ee;
            color: #1f6b38;
        }

        .empty-state {
            padding: 2rem;
            text-align: center;
            border: 1px solid #dbeaea;
            border-radius: 8px;
            color: #668181;
            background: #f8fcfc;
        }
    </style>

    <div class="reminders-page">
        <h1>Scheduled Reminders</h1>
        <p class="intro">Your Day 0, Day 3, and Day 7 vaccination reminders appear here after your appointment is approved.</p>

        <div class="reminder-list">
            @forelse($reminders as $reminder)
                @php
                    $isToday = $reminder->reminder_date?->isToday();
                    $isPast = $reminder->reminder_date?->isPast() && ! $isToday;
                @endphp
                <div class="reminder-card">
                    <div class="reminder-date">
                        <span>{{ optional($reminder->reminder_date)->format('M') }}</span>
                        <strong>{{ optional($reminder->reminder_date)->format('d') }}</strong>
                        <span>{{ optional($reminder->reminder_date)->format('Y') }}</span>
                    </div>
                    <div class="reminder-main">
                        <h2>{{ $reminder->dose_label }}</h2>
                        <div class="reminder-meta">
                            {{ $reminder->patient_name }}
                            @if($reminder->reminder_time)
                                &bull; {{ $reminder->reminder_time }}
                            @endif
                        </div>
                    </div>
                    @if($isToday)
                        <span class="reminder-status today">Due Today</span>
                    @elseif($isPast)
                        <span class="reminder-status done">Completed</span>
                    @else
                        <span class="reminder-status">Upcoming</span>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    No reminders yet. Once your appointment is approved, your three vaccination reminders will show here.
                </div>
            @endforelse
        </div>
    </div>
@endsection
