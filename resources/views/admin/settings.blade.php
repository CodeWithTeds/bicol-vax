@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<style>
    .settings-page-header {
        margin-bottom: 2rem;
    }
    .settings-page-header h1 {
        font-size: 1.9rem;
        color: #1f2937;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .settings-page-header p {
        color: #6b7280;
        font-size: 0.95rem;
    }

    .settings-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        margin-bottom: 1.75rem;
        overflow: hidden;
    }

    .settings-section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .settings-section-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #eef7f7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .settings-section-header h2 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.1rem;
    }

    .settings-section-header p {
        font-size: 0.82rem;
        color: #6b7280;
        margin: 0;
    }

    .settings-section-body {
        padding: 1.75rem;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .settings-grid.three-col {
        grid-template-columns: 1fr 1fr 1fr;
    }

    .settings-field {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .settings-field.full-width {
        grid-column: 1 / -1;
    }

    .settings-field label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
    }

    .settings-field input,
    .settings-field textarea,
    .settings-field select {
        padding: 0.65rem 0.9rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #1f2937;
        background: #fafafa;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        font-family: inherit;
    }

    .settings-field input:focus,
    .settings-field textarea:focus,
    .settings-field select:focus {
        border-color: #2b8f90;
        box-shadow: 0 0 0 3px rgba(43,143,144,0.1);
        background: white;
    }

    .settings-field textarea {
        resize: vertical;
        min-height: 70px;
    }

    /* Logo Upload */
    .logo-upload-area {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1rem;
        border: 1.5px dashed #d1d5db;
        border-radius: 10px;
        background: #fafafa;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
    }

    .logo-upload-area:hover {
        border-color: #2b8f90;
        background: #f0fafb;
    }

    .logo-preview {
        width: 68px;
        height: 68px;
        border-radius: 8px;
        object-fit: contain;
        background: white;
        border: 1px solid #e5e7eb;
        flex-shrink: 0;
    }

    .logo-upload-text {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }

    .logo-upload-text .upload-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2b8f90;
    }

    .logo-upload-text .upload-hint {
        font-size: 0.78rem;
        color: #9ca3af;
    }

    /* Notification checkboxes */
    .notification-list {
        display: grid;
        gap: 0.75rem;
    }

    .notification-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s;
        user-select: none;
    }

    .notification-item:hover {
        background: #f0fafb;
        border-color: #2b8f90;
    }

    .notification-item input[type="checkbox"] {
        width: 17px;
        height: 17px;
        accent-color: #2b8f90;
        cursor: pointer;
        flex-shrink: 0;
    }

    .notification-item span {
        font-size: 0.9rem;
        color: #374151;
        font-weight: 500;
    }

    /* Footer actions */
    .settings-footer {
        display: flex;
        justify-content: flex-end;
        padding-top: 1.25rem;
        border-top: 1px solid #f0f0f0;
        margin-top: 1.5rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #50c878 0%, #3aaa60 100%);
        color: white;
        border: none;
        padding: 0.7rem 1.75rem;
        border-radius: 8px;
        font-size: 0.92rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
    }

    .btn-save:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }

    .btn-save:active {
        transform: translateY(0);
    }

    /* Password strength */
    .password-hint {
        font-size: 0.77rem;
        color: #9ca3af;
        margin-top: 0.15rem;
    }

    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }
        .settings-grid.three-col {
            grid-template-columns: 1fr;
        }
        .settings-section-body {
            padding: 1.25rem;
        }
    }
</style>

<div class="settings-page-header">
    <h1>Settings</h1>
    <p>Manage your clinic settings and preferences.</p>
</div>

@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.9rem;font-weight:600;">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.9rem;font-weight:600;">
        ✗ {{ session('error') }}
    </div>
@endif

{{-- ─── Clinic Information ─────────────────────────────────────────── --}}
<div class="settings-section">
    <div class="settings-section-header">
        <div class="settings-section-icon">🏥</div>
        <div>
            <h2>Clinic Information</h2>
            <p>Update your clinic details and contact information.</p>
        </div>
    </div>
    <div class="settings-section-body">
        <form method="POST" action="{{ route('admin.settings.clinic') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="settings-grid">

                {{-- Left column --}}
                <div style="display:flex;flex-direction:column;gap:1.25rem;">
                    <div class="settings-field">
                        <label for="clinic_name">Clinic Name</label>
                        <input type="text" id="clinic_name" name="clinic_name"
                               value="{{ old('clinic_name', $branch->name ?? '') }}"
                               placeholder="BicolVax Clinic">
                    </div>
                    <div class="settings-field">
                        <label for="clinic_address">Address</label>
                        <input type="text" id="clinic_address" name="clinic_address"
                               value="{{ old('clinic_address', $branch->address ?? '') }}"
                               placeholder="San Francisco St., Baao, Camarines Sur">
                    </div>
                    <div class="settings-field">
                        <label for="clinic_email">Email Address</label>
                        <input type="email" id="clinic_email" name="clinic_email"
                               value="{{ old('clinic_email', $branch->email ?? '') }}"
                               placeholder="bicolvaxclinic@gmail.com">
                    </div>
                </div>

                {{-- Right column --}}
                <div style="display:flex;flex-direction:column;gap:1.25rem;">
                    <div class="settings-field">
                        <label>Clinic Logo</label>
                        <label for="clinic_logo" class="logo-upload-area">
                            @if($branch && $branch->logo_path)
                                <img src="{{ asset('storage/' . $branch->logo_path) }}" alt="Clinic Logo" class="logo-preview" id="logoPreview">
                            @else
                                <img src="{{ asset('logo.png') }}" alt="Clinic Logo" class="logo-preview" id="logoPreview">
                            @endif
                            <div class="logo-upload-text">
                                <span class="upload-label">⬆ Upload Logo</span>
                                <span class="upload-hint">JPG, PNG (max. 2MB)</span>
                            </div>
                            <input type="file" id="clinic_logo" name="clinic_logo" accept="image/jpeg,image/png" style="display:none;">
                        </label>
                    </div>
                    <div class="settings-field">
                        <label for="clinic_contact">Contact Number</label>
                        <input type="text" id="clinic_contact" name="clinic_contact"
                               value="{{ old('clinic_contact', $branch->contact ?? '') }}"
                               placeholder="+63 912 345 6789">
                    </div>
                    <div class="settings-field">
                        <label for="clinic_hours">Operating Hours</label>
                        <input type="text" id="clinic_hours" name="clinic_hours"
                               value="{{ old('clinic_hours', $branch->operating_hours ?? '') }}"
                               placeholder="Monday - Saturday, 8:00 AM - 5:00 PM">
                    </div>
                </div>
            </div>

            <div class="settings-footer">
                <button type="submit" class="btn-save">Save Clinic Information</button>
            </div>
        </form>
    </div>
</div>

{{-- ─── Administrator Account ──────────────────────────────────────── --}}
<div class="settings-section">
    <div class="settings-section-header">
        <div class="settings-section-icon">👤</div>
        <div>
            <h2>Administrator Account</h2>
            <p>Manage administrator account settings.</p>
        </div>
    </div>
    <div class="settings-section-body">
        <form method="POST" action="{{ route('admin.settings.account') }}">
            @csrf
            @method('PATCH')
            <div class="settings-grid">
                <div class="settings-field">
                    <label for="admin_name">Full Name</label>
                    <input type="text" id="admin_name" name="admin_name"
                           value="{{ old('admin_name', auth()->user()->name) }}"
                           placeholder="Admin User">
                </div>
                <div class="settings-field">
                    <label for="admin_username">Username</label>
                    <input type="text" id="admin_username" name="admin_username"
                           value="{{ old('admin_username', auth()->user()->username ?? '') }}"
                           placeholder="admin">
                </div>
                <div class="settings-field">
                    <label for="admin_email">Email</label>
                    <input type="email" id="admin_email" name="admin_email"
                           value="{{ old('admin_email', auth()->user()->email) }}"
                           placeholder="admin@bicolvax.com">
                </div>
                <div class="settings-field">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password"
                           placeholder="••••••••••">
                </div>
                <div class="settings-field">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password"
                           placeholder="Enter new password">
                    <span class="password-hint">Leave blank to keep current password.</span>
                </div>
                <div class="settings-field">
                    <label for="new_password_confirmation">Confirm Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                           placeholder="Confirm new password">
                </div>
            </div>

            <div class="settings-footer">
                <button type="submit" class="btn-save">Update Account</button>
            </div>
        </form>
    </div>
</div>

{{-- ─── Notification Settings ──────────────────────────────────────── --}}
<div class="settings-section">
    <div class="settings-section-header">
        <div class="settings-section-icon">🔔</div>
        <div>
            <h2>Notification Settings</h2>
            <p>Manage how notifications are sent.</p>
        </div>
    </div>
    <div class="settings-section-body">
        <form method="POST" action="{{ route('admin.settings.notifications') }}">
            @csrf
            @method('PATCH')
            <div class="notification-list">
                @php
                    $notifSettings = $notificationSettings ?? [];
                @endphp
                <label class="notification-item">
                    <input type="checkbox" name="enable_email_notifications" value="1"
                        {{ ($notifSettings['enable_email_notifications'] ?? true) ? 'checked' : '' }}>
                    <span>Enable Email Notifications</span>
                </label>
                <label class="notification-item">
                    <input type="checkbox" name="notify_patients_after_approval" value="1"
                        {{ ($notifSettings['notify_patients_after_approval'] ?? true) ? 'checked' : '' }}>
                    <span>Notify Patients After Approval</span>
                </label>
                <label class="notification-item">
                    <input type="checkbox" name="send_appointment_reminder" value="1"
                        {{ ($notifSettings['send_appointment_reminder'] ?? true) ? 'checked' : '' }}>
                    <span>Send Appointment Reminder (24 Hours Before)</span>
                </label>
                <label class="notification-item">
                    <input type="checkbox" name="send_vaccination_reminder" value="1"
                        {{ ($notifSettings['send_vaccination_reminder'] ?? true) ? 'checked' : '' }}>
                    <span>Send Vaccination Reminder</span>
                </label>
                <label class="notification-item">
                    <input type="checkbox" name="notify_staff_new_appointment" value="1"
                        {{ ($notifSettings['notify_staff_new_appointment'] ?? true) ? 'checked' : '' }}>
                    <span>Notify Staff of New Appointment</span>
                </label>
            </div>

            <div class="settings-footer">
                <button type="submit" class="btn-save">Save Notification Settings</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Live logo preview
    document.getElementById('clinic_logo').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('logoPreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection
