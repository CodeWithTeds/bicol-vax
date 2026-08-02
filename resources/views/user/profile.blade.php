@extends('layouts.user')

@section('title', 'Profile')

@section('content')
    <style>
        .profile-page {
            max-width: 1000px;
            margin: 0 auto;
        }

        .profile-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .profile-top h1 {
            font-size: 1.8rem;
            color: #243b3b;
            margin-bottom: 0.25rem;
        }

        .profile-top p {
            color: #456;
            font-size: 0.95rem;
        }

        .btn-edit {
            background-color: #2b8f90;
            color: white;
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn-edit:hover {
            background-color: #1f6566;
        }

        .info-card {
            background: #e8f7f6;
            border: 1px solid #c7dede;
            border-radius: 12px;
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .info-card h2 {
            font-size: 1.3rem;
            color: #243b3b;
            margin-bottom: 0.25rem;
        }

        .info-card .subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem 3rem;
        }

        .profile-photo-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .profile-photo {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            object-fit: cover;
            background: #d9eeee;
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(32, 89, 90, 0.18);
        }

        .profile-photo-empty {
            display: grid;
            place-items: center;
            color: #226d6e;
            font-size: 1.3rem;
            font-weight: 800;
        }

        .info-field {
            border-bottom: 1.5px solid #c7dede;
            padding-bottom: 0.5rem;
        }

        .info-field label {
            display: block;
            font-size: 0.8rem;
            color: #243b3b;
            margin-bottom: 0.3rem;
            font-weight: 600;
        }

        .info-field span {
            display: block;
            font-size: 1rem;
            color: #243b3b;
            font-weight: 500;
        }

        /* Edit Modal / Form */
        .edit-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .edit-overlay.active {
            display: flex;
        }

        .edit-modal {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .edit-modal h2 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #243b3b;
            margin-bottom: 0.4rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.6rem 0.9rem;
            border: 1px solid #c7dede;
            border-radius: 6px;
            font-size: 0.9rem;
            background: #fff;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2b8f90;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .btn-cancel {
            padding: 0.6rem 1.5rem;
            border: 1px solid #c7dede;
            border-radius: 6px;
            background: #d9e7e7;
            color: #1f2f2f;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #c7dede;
        }

        .btn-save {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 6px;
            background-color: #2b8f90;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-save:hover {
            background-color: #1f6566;
        }

        .alert-success {
            background-color: #e8f7ee;
            color: #1f6b38;
            padding: 0.85rem 1.25rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border: 1px solid #bfe8cb;
        }

        .alert-error {
            background-color: #fdecec;
            color: #9b1c1c;
            padding: 0.85rem 1.25rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            border: 1px solid #f5b5b5;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .profile-top {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>

    <div class="profile-page">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="profile-top">
            <div>
                <h1>Profile</h1>
                <p>View and update your personal information</p>
            </div>
            <button class="btn-edit" onclick="document.getElementById('editModal').classList.add('active')">Edit Profile</button>
        </div>

        <div class="info-card">
            <h2>Personal Information</h2>
            <p class="subtitle">Your account details and contact information</p>

            <div class="info-grid">
                <div class="info-field">
                    <label>Profile Photo</label>
                    @if(!empty($patient?->profile_photo_path))
                        <img class="profile-photo" src="{{ '/storage/' . ltrim($patient->profile_photo_path, '/') }}" alt="Profile photo">
                    @else
                        <span>No photo uploaded</span>
                    @endif
                </div>
                <div class="info-field">
                    <label>Fullname</label>
                    <span>{{ $patient->full_name ?? $user->name ?? 'N/A' }}</span>
                </div>
                <div class="info-field">
                    <label>Email</label>
                    <span>{{ $user->email ?? 'N/A' }}</span>
                </div>
                <div class="info-field">
                    <label>Gender</label>
                    <span>{{ $patient->gender ?? 'N/A' }}</span>
                </div>
                <div class="info-field">
                    <label>Contact</label>
                    <span>{{ $patient->contact ?? 'N/A' }}</span>
                </div>
                <div class="info-field">
                    <label>Age</label>
                    <span>{{ $patient->age ?? 'N/A' }}</span>
                </div>
                <div class="info-field">
                    <label>Address</label>
                    <span>{{ $patient->address ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <div class="info-card">
            <h2>Registration Details</h2>
            <p class="subtitle">Your card number and registration status</p>

            <div class="info-grid">
                <div class="info-field">
                    <label>Card Number</label>
                    <span>{{ $patient->card_no ?? 'Not assigned' }}</span>
                </div>
                <div class="info-field">
                    <label>Case Number</label>
                    <span>{{ $patient->case_no ?? 'Not assigned' }}</span>
                </div>
                <div class="info-field">
                    <label>Status</label>
                    <span>{{ $patient ? ($patient->status === 'approved' ? 'Approved' : 'Pending Approval') : 'No Record' }}</span>
                </div>
                <div class="info-field">
                    <label>Source</label>
                    <span>{{ ucfirst($patient->source ?? 'N/A') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Profile Modal --}}
    <div class="edit-overlay" id="editModal">
        <div class="edit-modal">
            <h2>Edit Profile</h2>
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="profile-photo-row">
                    @if(!empty($patient?->profile_photo_path))
                        <img class="profile-photo" src="{{ '/storage/' . ltrim($patient->profile_photo_path, '/') }}" alt="Current profile photo">
                    @else
                        <div class="profile-photo profile-photo-empty">
                            {{ strtoupper(substr($patient->full_name ?? $user->name ?? 'P', 0, 1)) }}
                        </div>
                    @endif
                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <label for="profile_photo">Profile Photo</label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/webp">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $patient->full_name ?? $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="text" id="contact" name="contact" value="{{ old('contact', $patient->contact ?? '') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="male" {{ strtolower($patient->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ strtolower($patient->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ strtolower($patient->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" value="{{ old('age', $patient->age ?? '') }}" min="1" max="150">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $patient->address ?? '') }}">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="document.getElementById('editModal').classList.remove('active')">Cancel</button>
                    <button type="submit" class="btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Close modal on overlay click
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    </script>
@endsection
