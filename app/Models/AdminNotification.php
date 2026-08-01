<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'icon',
        'is_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
        ];
    }

    /**
     * Scope: unread notifications only.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark this notification as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Create a notification for an appointment confirmation.
     */
    public static function appointmentConfirmed(string $patientName, ?string $date = null): self
    {
        return static::create([
            'type' => 'appointment_confirmed',
            'title' => 'Appointment Confirmed',
            'message' => "{$patientName}'s appointment has been confirmed" . ($date ? " for {$date}." : '.'),
            'icon' => '✅',
        ]);
    }

    /**
     * Create a notification for a status update.
     */
    public static function statusUpdated(string $patientName, string $newStatus): self
    {
        $statusLabel = $newStatus === 'approved' ? 'Approved' : 'Not Approved';

        return static::create([
            'type' => 'status_update',
            'title' => 'Status Updated',
            'message' => "{$patientName} has been marked as {$statusLabel}.",
            'icon' => '🔄',
        ]);
    }

    /**
     * Create a system announcement notification.
     */
    public static function systemAnnouncement(string $title, string $message): self
    {
        return static::create([
            'type' => 'system_announcement',
            'title' => $title,
            'message' => $message,
            'icon' => '📢',
        ]);
    }

    /**
     * Create a notification for a new appointment request.
     */
    public static function newAppointmentRequest(string $patientName): self
    {
        return static::create([
            'type' => 'new_appointment',
            'title' => 'New Appointment Request',
            'message' => "{$patientName} has submitted a new appointment request.",
            'icon' => '📅',
        ]);
    }

    /**
     * Create a notification for a new patient registration.
     */
    public static function newPatientRegistration(string $patientName): self
    {
        return static::create([
            'type' => 'new_registration',
            'title' => 'New Patient Registration',
            'message' => "{$patientName} has registered online and is awaiting approval.",
            'icon' => '👤',
        ]);
    }
}
