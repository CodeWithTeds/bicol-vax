<?php

use App\Http\Controllers\AdminController;
use App\Models\Patient;
use Illuminate\Http\Request;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = $argv[1] ?? null;
if (! $email) {
    echo "Usage: php approve_registration.php email@example.com\n";
    exit(1);
}

$patient = Patient::where('email', $email)->where('source', 'web')->first();
if (! $patient) {
    echo "No web registration found for: $email\n";
    exit(1);
}

$request = Request::create('/admin/patients/' . $patient->id . '/status', 'POST', ['status' => 'approved']);

$controller = new AdminController();

try {
    $resp = $controller->updatePatientStatus($request, $patient);
    echo "OK: ";
    if (is_object($resp) || is_array($resp)) {
        echo json_encode(method_exists($resp, 'getContent') ? $resp->getContent() : $resp);
    } else {
        echo "Response returned\n";
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
