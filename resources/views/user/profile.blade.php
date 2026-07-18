@extends('layouts.user')

@section('title', 'Profile')

@section('content')
    <style>
        .profile-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .profile-card.full-width {
            grid-column: 1 / -1;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2b8f90 0%, #50c878 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: white;
        }

        .profile-name h2 {
            margin: 0;
            color: #333;
            font-size: 1.5rem;
        }

        .profile-name p {
            margin: 0.25rem 0 0;
            color: #666;
            font-size: 0.9rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2b8f90;
            margin-bottom: 1.25rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #8fd8cc;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .info-item {
            padding: 0.75rem;
            background: #f8fffe;
            border-radius: 8px;
            border-left: 3px solid #2b8f90;
        }

        .info-item label {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .info-item span {
            font-size: 0.95rem;
            color: #333;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.85rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-not_approved {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Edit Form */
        .edit-form {
            margin-top: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 0.4rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.65rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2b8f90;
            box-shadow: 0 0 0 3px rgba(43, 143, 144, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-save {
            background: linear-gradient(135deg, #2b8f90 0%, #1f6566 100%);
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 0.5rem;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(43, 143, 144, 0.3);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .profile-container {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-header">
        <h1>👤 Profile</h1>
        <p>View and manage your personal information. Your card number and registration status are shown below.</p>
    </div>

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

    <div class="profile-container">
        {{-- Profile Header Card --}}
        <div class="profile-card full-width">
            <div class="profile-header">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="profile-name">
                    <h2>{{ $user->name ?? 'User' }}</h2>
                    <p>{{ $user->email }}</p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label>Card Number</label>
                    <span>{{ $patient->card_no ?? 'Not assigned' }}</span>
                </div>
                <div class="info-item">
                    <label>Case Number</label>
                    <span>{{ $patient->case_no ?? 'Not assigned' }}</span>
                </div>
                <div class="info-item">
                    <label>Registration Status</label>
                    <span>
                        @if($patient)
                            <span class="status-badge status-{{ $patient->status }}">
                                {{ $patient->status === 'approved' ? 'Approved' : 'Pending Approval' }}
                            </span>
                        @else
                            <span class="status-badge status-not_approved">No Record</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <label>Source</label>
                    <span>{{ ucfirst($patient->source ?? 'N/A') }}</span>
                </div>
            </div>
        </div>

        {{-- Personal Information --}}
        <div class="profile-card">
            <h3 class="card-title">Personal Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Full Name</label>
                    <span>{{ $patient->full_name ?? $user->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="info-item">
                    <label>Contact</label>
                    <span>{{ $patient->contact ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Age</label>
                    <span>{{ $patient->age ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Gender</label>
                    <span>{{ $patient->gender ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Address</label>
                    <span>{{ $patient->address ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- Medical Information --}}
        <div class="profile-card">
            <h3 class="card-title">Medical Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Weight</label>
                    <span>{{ $patient ? $patient->weight . ' kg' : 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Category</label>
                    <span>{{ $patient->cat_category ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Bite Type</label>
                    <span>{{ $patient->bite_type ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Severity</label>
                    <span>{{ ucfirst($patient->severity ?? 'N/A') }}</span>
                </div>
                <div class="info-item">
                    <label>Place of Bite</label>
                    <span>{{ $patient->place_of_bite ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label>Tetanus Status</label>
                    <span>{{ $patient->tetanus_status ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        {{-- Edit Profile --}}
        <div class="profile-card full-width">
            <h3 class="card-title">Edit Profile</h3>
            <form action="{{ route('user.profile.update') }}" method="POST" class="edit-form">
                @csrf
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
                            <option value="Male" {{ ($patient->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ ($patient->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ ($patient->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
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
                <button type="submit" class="btn-save">Save Changes</button>
            </form>
        </div>
    </div>
@endsection
