<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'user_id',
        'patient_id',
        'full_name',
        'birthday',
        'age',
        'gender',
        'address',
        'contact',
        'appointment_date',
        'appointment_time',
        'parent_guardian',
        'generated_password',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birthday' => 'date',
            'appointment_date' => 'date',
            'appointment_time' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
