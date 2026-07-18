<?php

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PatientApprovedMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'markdecierra1700@gmail.com';

$appointment = Appointment::whereNotNull('generated_password')
    ->where('status', 'not_approved')
    ->with('user')
    ->first();

if (! $appointment) {
    // create/find a test user
    $user = User::where('email', $email)->first();
    if (! $user) {
        $user = User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => Hash::make(Str::random(12)),
        ]);
    }

    $generated = Str::random(12);

    $appointment = Appointment::create([
        'user_id' => $user->id,
        'full_name' => $user->name,
        'birthday' => now()->subYears(30)->toDateString(),
        'age' => 30,
        'gender' => 'other',
        'address' => 'Test address',
        'contact' => '0000000000',
        'appointment_date' => now()->addDays(7)->toDateString(),
        'parent_guardian' => null,
        'generated_password' => $generated,
        'status' => 'not_approved',
    ]);
}

$fullName = $appointment->full_name;
$password = $appointment->generated_password;

try {
    Mail::to($email)->send(new PatientApprovedMail($fullName, $password));
    // clear stored password and mark approved
    $appointment->generated_password = null;
    $appointment->status = 'approved';
    $appointment->save();
    echo "EMAIL_SENT\n";
    exit(0);
} catch (\Exception $e) {
    echo "EMAIL_ERROR: " . $e->getMessage() . "\n";
    exit(2);
}
