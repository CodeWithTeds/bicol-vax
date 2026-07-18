<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Appointment;

$appointments = Appointment::latest()->take(10)->get();
foreach ($appointments as $a) {
    echo "ID: {$a->id} | Name: {$a->full_name} | Email: " . ($a->user?->email ?? 'NULL') . " | Status: {$a->status} | Generated: " . ($a->generated_password ? 'YES' : 'NO') . " | Created: {$a->created_at}\n";
}
