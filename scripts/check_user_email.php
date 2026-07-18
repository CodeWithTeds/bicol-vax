<?php

use App\Models\User;
use App\Models\Appointment;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = $argv[1] ?? null;
if (! $email) {
    echo "Usage: php scripts/check_user_email.php someone@example.com\n";
    exit(1);
}

$user = User::where('email', $email)->first();
if (! $user) {
    echo "USER_NOT_FOUND\n";
    exit(0);
}

$data = [
    'id' => $user->id,
    'name' => $user->name,
    'email' => $user->email,
    'created_at' => (string) $user->created_at,
    'appointments' => Appointment::where('user_id', $user->id)->get()->map(function($a) {
        return [
            'id' => $a->id,
            'full_name' => $a->full_name,
            'status' => $a->status,
            'appointment_date' => (string) $a->appointment_date,
            'created_at' => (string) $a->created_at,
        ];
    })->toArray(),
];

echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
