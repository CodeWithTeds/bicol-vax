<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = $argv[1] ?? 'decierramarkdelgaco@gmail.com';

// Minimal registration payload: only email and password are required now
$data = [
    'email' => $email,
    'fullname' => 'Deierra Mark',
    'contact' => '09181234567',
    'password' => 'Password123',
    'password_confirmation' => 'Password123',
];

$request = Request::create('/register', 'POST', $data);

$controller = new UserController();

try {
    $resp = $controller->registerPatient($request);
    echo "OK: ";
    if (is_object($resp) || is_array($resp)) {
        echo json_encode(method_exists($resp, 'getContent') ? $resp->getContent() : $resp);
    } else {
        echo "Response returned\n";
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
