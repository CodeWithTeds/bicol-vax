<?php

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'ui-test-' . time() . '@example.com';

$user = User::create([
    'name' => 'UI Test User ' . time(),
    'email' => $email,
    'password' => Hash::make(Str::random(12)),
]);

$appointment = Appointment::create([
    'user_id' => $user->id,
    'full_name' => $user->name,
    'birthday' => now()->subYears(25)->toDateString(),
    'age' => 25,
    'gender' => 'other',
    'address' => 'Scripted Test Address',
    'contact' => '09191234567',
    'appointment_date' => now()->addDays(3)->toDateString(),
    'parent_guardian' => null,
    'generated_password' => null,
    'status' => 'not_approved',
]);

echo "CREATED_APPOINTMENT: {$appointment->id} for {$email}\n";
