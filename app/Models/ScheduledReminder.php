<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'user_id',
        'patient_name',
        'email',
        'dose_label',
        'day_offset',
        'reminder_date',
        'reminder_time',
        'sent_at',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'reminder_date' => 'date',
            'sent_at' => 'datetime',
            'read_at' => 'datetime',
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function syncForAppointment(Appointment $appointment, ?string $email = null): void
    {
        if (! $appointment->appointment_date) {
            return;
        }

        $doses = [
            ['label' => 'Day 0 Vaccination', 'offset' => 0],
            ['label' => 'Day 3 Vaccination', 'offset' => 3],
            ['label' => 'Day 7 Vaccination', 'offset' => 7],
        ];

        foreach ($doses as $dose) {
            self::updateOrCreate(
                [
                    'appointment_id' => $appointment->id,
                    'day_offset' => $dose['offset'],
                ],
                [
                    'user_id' => $appointment->user_id,
                    'patient_name' => $appointment->full_name,
                    'email' => $email,
                    'dose_label' => $dose['label'],
                    'reminder_date' => $appointment->appointment_date->copy()->addDays($dose['offset'])->toDateString(),
                    'reminder_time' => $appointment->appointment_time,
                ]
            );
        }
    }
}
