<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SuperAdminController;

Route::get('/', function () {
    $adminEmail = User::query()
        ->where('is_admin', true)
        ->value('email');

    return view('welcome', compact('adminEmail'));
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::post('/register', [UserController::class, 'registerPatient'])->name('public.register');
// Public patient login (used by the login modal)
Route::post('/login', [UserController::class, 'login'])->name('public.login');

// Admin auth routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::get('otp', [AdminAuthController::class, 'showVerify'])->name('otp.verify');
    Route::post('otp', [AdminAuthController::class, 'verify'])->name('otp.verify.post');
});

// Protected admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.otp'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
    Route::post('/patients', [AdminController::class, 'storePatient'])->name('patients.store');
    Route::patch('/patients/{patient}', [AdminController::class, 'updatePatient'])->name('patients.update');
    Route::post('/patients/{patient}/status', [AdminController::class, 'updatePatientStatus'])->name('patients.status');
    Route::delete('/patients/{patient}', [AdminController::class, 'destroyPatient'])->name('patients.destroy');
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
    Route::get('/appointments/json', [AdminController::class, 'appointmentsJson'])->name('appointments.json');
    Route::post('/appointments/{appointment}/status', [AdminController::class, 'updateAppointmentStatus'])->name('appointments.status');
    Route::post('/appointments/{appointment}/patient/{patient}/treatment', [AdminController::class, 'updatePatientTreatment'])->name('appointments.treatment');
    Route::delete('/appointments/{appointment}', [AdminController::class, 'destroyAppointment'])->name('appointments.destroy');
    Route::get('/reminders', [AdminController::class, 'reminders'])->name('reminders');
    Route::post('/reminders/{reminder}/send', [AdminController::class, 'sendReminder'])->name('reminders.send');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/json', [AdminController::class, 'notificationsJson'])->name('notifications.json');
    Route::post('/notifications/{notification}/read', [AdminController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [AdminController::class, 'markAllNotificationsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{notification}', [AdminController::class, 'destroyNotification'])->name('notifications.destroy');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});

// User routes
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/records', [UserController::class, 'records'])->name('records');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/booking', [UserController::class, 'booking'])->name('booking');
    Route::post('/booking', [UserController::class, 'storeBooking'])->name('booking.store');
    Route::get('/my-appointments', [UserController::class, 'myAppointments'])->name('my-appointments');
    Route::get('/reminders', [UserController::class, 'reminders'])->name('reminders');
});

// Super Admin routes
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('login',  [SuperAdminController::class, 'showLogin'])->name('login');
    Route::post('login', [SuperAdminController::class, 'login'])->name('login.post');
});

Route::prefix('superadmin')->name('superadmin.')->middleware('superadmin')->group(function () {
    Route::post('logout', [SuperAdminController::class, 'logout'])->name('logout');

    Route::get('dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // Branch management
    Route::get('branches',                 [SuperAdminController::class, 'branches'])->name('branches');
    Route::post('branches',                [SuperAdminController::class, 'storeBranch'])->name('branches.store');
    Route::patch('branches/{branch}',      [SuperAdminController::class, 'updateBranch'])->name('branches.update');
    Route::delete('branches/{branch}',     [SuperAdminController::class, 'destroyBranch'])->name('branches.destroy');
    Route::get('branches/{branch}/view',   [SuperAdminController::class, 'viewBranch'])->name('branches.view');

    // Branch admin management
    Route::get('admins',               [SuperAdminController::class, 'admins'])->name('admins');
    Route::post('admins',              [SuperAdminController::class, 'storeAdmin'])->name('admins.store');
    Route::patch('admins/{user}',      [SuperAdminController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('admins/{user}',     [SuperAdminController::class, 'destroyAdmin'])->name('admins.destroy');

    // Reports
    Route::get('reports', [SuperAdminController::class, 'reports'])->name('reports');
});

// Logout route
Route::post('/logout', function () {
    request()->session()->forget(['admin_otp_user', 'admin_otp_verified']);
    auth()->logout();
    return redirect('/');
})->name('logout');
