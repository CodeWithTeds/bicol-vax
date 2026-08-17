@extends('layouts.user')

@section('title', 'Booking Appointment')

@section('content')
<style>
    .booking-wrap { max-width: 1020px; margin: 0 auto; }

    .booking-form {
        background: #eef4f3;
        padding: 1.5rem;
        border-radius: 10px;
    }

    .section-header {
        background: #2b8f90;
        color: #fff;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 1rem;
        margin: 1.25rem 0 1rem;
    }

    .section-header:first-of-type { margin-top: 0; }

    .two-col-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .field-row {
        display: grid;
        grid-template-columns: 160px 1fr;
        align-items: center;
        margin-bottom: 0.65rem;
        gap: 0.5rem;
    }

    .field-label {
        font-weight: 600;
        font-size: 0.88rem;
        color: #243b3b;
    }

    .field-input {
        padding: 0.45rem 0.7rem;
        border: 1px solid #b5d0d0;
        border-radius: 5px;
        background: #f0f5f5;
        font: inherit;
        font-size: 0.88rem;
        width: 100%;
    }

    .field-input:focus {
        outline: none;
        border-color: #2b8f90;
        background: #fff;
    }

    select.field-input { cursor: pointer; }

    .radio-group {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .radio-group label {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.88rem;
        cursor: pointer;
    }

    .checkbox-yn {
        display: grid;
        grid-template-columns: 40px 40px 1fr;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.5rem;
        font-size: 0.88rem;
    }

    .checkbox-yn .yn-head {
        font-size: 0.78rem;
        font-weight: 700;
        color: #5a7070;
        text-align: center;
    }

    .checkbox-yn input[type="checkbox"] {
        margin: 0 auto;
        display: block;
        cursor: pointer;
        accent-color: #2b8f90;
    }

    .section-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .confirm-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 1rem 0;
        border-top: 1px solid #c5d8d8;
        margin-top: 1rem;
        font-size: 0.9rem;
    }

    .confirm-row input { accent-color: #2b8f90; width: 17px; height: 17px; cursor: pointer; }

    .actions {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        margin-top: 0.5rem;
    }

    .btn {
        border: 0; border-radius: 7px;
        padding: 0.8rem 2.5rem;
        font-weight: 700; font-size: 0.95rem;
        cursor: pointer;
    }

    .btn-primary { background: #2b8f90; color: #fff; }
    .btn-primary:hover { background: #237778; }
    .btn-secondary { background: #d9e7e7; color: #1f2f2f; }

    @media (max-width: 860px) {
        .two-col-layout, .section-columns { grid-template-columns: 1fr; }
        .field-row { grid-template-columns: 1fr; }
    }
</style>

<div class="booking-wrap">
    <h1 style="margin-bottom: 0.25rem;">Online Appointment Booking Form</h1>

    @if(session('success'))
        <div style="margin:0.75rem 0;padding:0.85rem 1rem;border-radius:6px;background:#e8f7ee;color:#1f6b38;border:1px solid #bfe8cb;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="margin:0.75rem 0;padding:0.85rem 1rem;border-radius:6px;background:#fdecec;color:#9b1c1c;border:1px solid #f5b5b5;">
            <strong>Please fix the following:</strong>
            <ul style="margin:0.5rem 0 0 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="booking-form" method="POST" action="{{ route('user.booking.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- ── PATIENT INFORMATION ── --}}
        <div class="section-header">Patient Information</div>
        <div class="two-col-layout">
            {{-- Left column --}}
            <div>
                <div class="field-row">
                    <span class="field-label">Full Name:</span>
                    <input class="field-input" type="text" name="full_name" value="{{ old('full_name', $patient?->full_name ?? auth()->user()?->name) }}" placeholder="Full name">
                </div>
                <div class="field-row">
                    <span class="field-label">Age:</span>
                    <input class="field-input" type="number" name="age" min="0" max="150" value="{{ old('age', $patient?->age ?? 18) }}" placeholder="18">
                </div>
                <div class="field-row">
                    <span class="field-label">Gender:</span>
                    <select class="field-input" name="gender">
                        <option value="">Gender:</option>
                        <option value="male"   {{ old('gender', $patient?->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $patient?->gender) === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other"  {{ old('gender', $patient?->gender) === 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="field-row">
                    <span class="field-label">Birthday:</span>
                    <input class="field-input" type="date" name="birthday" value="{{ old('birthday', $patient?->birthday?->format('Y-m-d')) }}">
                </div>
            </div>

            {{-- Right column --}}
            <div>
                <div class="field-row">
                    <span class="field-label">Address:</span>
                    <input class="field-input" type="text" name="address" value="{{ old('address', $patient?->address) }}" placeholder="Home address">
                </div>
                <div class="field-row">
                    <span class="field-label">Contact number:</span>
                    <input class="field-input" type="tel" name="contact" value="{{ old('contact', $patient?->contact) }}" placeholder="09XX-XXX-XXXX">
                </div>
                <div class="field-row">
                    <span class="field-label">Email:</span>
                    <input class="field-input" type="email" name="email" value="{{ old('email', auth()->user()?->email ?? $patient?->email) }}" placeholder="email@example.com">
                </div>
                <div class="field-row">
                    <span class="field-label">Parent/guardian (if minor):</span>
                    <input class="field-input" type="text" name="parent_guardian" value="{{ old('parent_guardian') }}" placeholder="Guardian name">
                </div>
            </div>
        </div>

        {{-- Appointment date & wound photo --}}
        <div class="two-col-layout" style="margin-top:0.5rem;">
            <div class="field-row">
                <span class="field-label">Preferred Appointment Date *</span>
                <input class="field-input" type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" required min="{{ date('Y-m-d') }}">
            </div>
            <div>
                <span class="field-label" style="display:block;margin-bottom:0.4rem;">Wound / Bite Photo (optional)</span>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div id="photoPreview" style="width:90px;height:75px;border:1px dashed #8dbcbc;border-radius:5px;background:#f0f5f5;display:grid;place-items:center;font-size:0.72rem;color:#527272;overflow:hidden;flex:0 0 90px;">
                        @if(!empty($patient?->profile_photo_path))
                            <img src="{{ '/storage/' . ltrim($patient->profile_photo_path, '/') }}" style="width:100%;height:100%;object-fit:cover;" alt="Photo">
                        @else
                            No photo
                        @endif
                    </div>
                    <input type="file" name="profile_photo" id="profile_photo" accept="image/jpeg,image/png,image/webp">
                </div>
            </div>
        </div>

        {{-- ── ANIMAL BITE INFO + MEDICAL HISTORY ── --}}
        <div class="section-columns" style="margin-top:0.5rem;">

            {{-- LEFT: Animal Bite Information --}}
            <div>
                <div class="section-header" style="margin-top:0;">Animal Bite information</div>

                <div class="field-row">
                    <span class="field-label">Nature of Bite (NOB):</span>
                    <select class="field-input" name="animal_type">
                        <option value="">Select Source</option>
                        <option value="dog"    {{ old('animal_type') === 'dog'    ? 'selected' : '' }}>Dog</option>
                        <option value="cat"    {{ old('animal_type') === 'cat'    ? 'selected' : '' }}>Cat</option>
                        <option value="bat"    {{ old('animal_type') === 'bat'    ? 'selected' : '' }}>Bat</option>
                        <option value="rat"    {{ old('animal_type') === 'rat'    ? 'selected' : '' }}>Rat</option>
                        <option value="monkey" {{ old('animal_type') === 'monkey' ? 'selected' : '' }}>Monkey</option>
                        <option value="other"  {{ old('animal_type') === 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="field-row">
                    <span class="field-label">Pet / Stray:</span>
                    <div class="radio-group">
                        <label><input type="checkbox" name="pet_or_stray" value="pet"   {{ old('pet_or_stray') === 'pet'   ? 'checked' : '' }}> Pet</label>
                        <label><input type="checkbox" name="pet_or_stray" value="stray" {{ old('pet_or_stray') === 'stray' ? 'checked' : '' }}> Stray</label>
                    </div>
                </div>

                <div class="field-row">
                    <span class="field-label">Vaccinated Animal:</span>
                    <div class="radio-group">
                        <label><input type="radio" name="vaccinated_animal" value="yes" {{ old('vaccinated_animal') === 'yes' ? 'checked' : '' }}> Yes</label>
                        <label><input type="radio" name="vaccinated_animal" value="no"  {{ old('vaccinated_animal') === 'no'  ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="field-row">
                    <span class="field-label">Animal Status:</span>
                    <input class="field-input" type="text" name="animal_status" value="{{ old('animal_status') }}" placeholder="e.g. alive, dead, stray">
                </div>

                <div class="field-row">
                    <span class="field-label">Date of Bite:</span>
                    <input class="field-input" type="date" name="date_of_bite" value="{{ old('date_of_bite') }}">
                </div>

                <div class="field-row">
                    <span class="field-label">Site of Bite:</span>
                    <select class="field-input" name="place_of_bite">
                        <option value="">Select Site Bite</option>
                        <option value="hand"          {{ old('place_of_bite') === 'hand'          ? 'selected' : '' }}>Hand</option>
                        <option value="arm"           {{ old('place_of_bite') === 'arm'           ? 'selected' : '' }}>Arm</option>
                        <option value="leg"           {{ old('place_of_bite') === 'leg'           ? 'selected' : '' }}>Leg</option>
                        <option value="foot"          {{ old('place_of_bite') === 'foot'          ? 'selected' : '' }}>Foot</option>
                        <option value="face"          {{ old('place_of_bite') === 'face'          ? 'selected' : '' }}>Face</option>
                        <option value="neck"          {{ old('place_of_bite') === 'neck'          ? 'selected' : '' }}>Neck</option>
                        <option value="finger"        {{ old('place_of_bite') === 'finger'        ? 'selected' : '' }}>Finger</option>
                        <option value="multiple_sites"{{ old('place_of_bite') === 'multiple_sites'? 'selected' : '' }}>Multiple sites</option>
                    </select>
                </div>

                <div class="field-row">
                    <span class="field-label">Severity:</span>
                    <select class="field-input" name="severity">
                        <option value="">Select Category</option>
                        <option value="mild"     {{ old('severity') === 'mild'     ? 'selected' : '' }}>Mild</option>
                        <option value="moderate" {{ old('severity') === 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="severe"   {{ old('severity') === 'severe'   ? 'selected' : '' }}>Severe</option>
                    </select>
                </div>

                <div class="field-row">
                    <span class="field-label">Washing of Wound:</span>
                    <div class="radio-group">
                        <label><input type="radio" name="washing_of_wound" value="yes" {{ old('washing_of_wound') === 'yes' ? 'checked' : '' }}> Yes</label>
                        <label><input type="radio" name="washing_of_wound" value="no"  {{ old('washing_of_wound') === 'no'  ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="field-row">
                    <span class="field-label">Tandok/Tambal:</span>
                    <div class="radio-group">
                        <label><input type="radio" name="tandok_tambal" value="yes" {{ old('tandok_tambal') === 'yes' ? 'checked' : '' }}> Yes</label>
                        <label><input type="radio" name="tandok_tambal" value="no"  {{ old('tandok_tambal') === 'no'  ? 'checked' : '' }}> No</label>
                    </div>
                </div>

                <div class="field-row">
                    <span class="field-label">Owner Name:</span>
                    <input class="field-input" type="text" name="owner_name" value="{{ old('owner_name') }}" placeholder="Animal owner name">
                </div>

                <div class="field-row">
                    <span class="field-label">Owner Address:</span>
                    <input class="field-input" type="text" name="owner_address" value="{{ old('owner_address') }}" placeholder="Owner address">
                </div>
            </div>

            {{-- RIGHT: Medical History --}}
            <div>
                <div class="section-header" style="margin-top:0;">Medical History</div>

                {{-- Yes/No header row --}}
                <div style="display:grid;grid-template-columns:40px 40px 1fr 40px 40px 1fr;gap:0.4rem;font-size:0.78rem;font-weight:700;color:#5a7070;margin-bottom:0.35rem;text-align:center;">
                    <span>Yes</span><span>No</span><span></span>
                    <span>Yes</span><span>No</span><span></span>
                </div>

                @php
                    $conditions = [
                        ['name'=>'has_diabetes',        'label'=>'Diabetes (IDDM)'],
                        ['name'=>'has_cancer',          'label'=>'Cancer'],
                        ['name'=>'has_organ_transplant','label'=>'Organ Transplant'],
                        ['name'=>'has_ckd',             'label'=>'CKD riv'],
                    ];
                    $conditions2 = [
                        ['name'=>'has_hiv',      'label'=>'HIV'],
                        ['name'=>'taking_steroid','label'=>'Taking Steroid'],
                        ['name'=>'has_riv',      'label'=>'riv'],
                    ];
                @endphp

                @for($i = 0; $i < max(count($conditions), count($conditions2)); $i++)
                    <div style="display:grid;grid-template-columns:40px 40px 1fr 40px 40px 1fr;gap:0.4rem;align-items:center;margin-bottom:0.4rem;font-size:0.88rem;">
                        @if(isset($conditions[$i]))
                            <input type="checkbox" name="{{ $conditions[$i]['name'] }}" value="1" {{ old($conditions[$i]['name']) ? 'checked' : '' }} style="margin:auto;accent-color:#2b8f90;">
                            <input type="checkbox" name="{{ $conditions[$i]['name'] }}_no" value="1" style="margin:auto;accent-color:#9b1c1c;">
                            <span>{{ $conditions[$i]['label'] }}</span>
                        @else
                            <span></span><span></span><span></span>
                        @endif
                        @if(isset($conditions2[$i]))
                            <input type="checkbox" name="{{ $conditions2[$i]['name'] }}" value="1" {{ old($conditions2[$i]['name']) ? 'checked' : '' }} style="margin:auto;accent-color:#2b8f90;">
                            <input type="checkbox" name="{{ $conditions2[$i]['name'] }}_no" value="1" style="margin:auto;accent-color:#9b1c1c;">
                            <span>{{ $conditions2[$i]['label'] }}</span>
                        @else
                            <span></span><span></span><span></span>
                        @endif
                    </div>
                @endfor

                <div class="field-row" style="margin-top:0.75rem;">
                    <span class="field-label">Allergy:</span>
                    <input class="field-input" type="text" name="allergy" value="{{ old('allergy') }}" placeholder="Any known allergies">
                </div>

                <div class="field-row">
                    <span class="field-label">Weight:</span>
                    <input class="field-input" type="number" step="0.1" name="weight" value="{{ old('weight', $patient?->weight) }}" placeholder="kg">
                </div>

                <div class="field-row">
                    <span class="field-label">Blood Pressure:</span>
                    <input class="field-input" type="text" name="blood_pressure" value="{{ old('blood_pressure') }}" placeholder="e.g. 120/80">
                </div>

                <div class="field-row">
                    <span class="field-label">Temperature:</span>
                    <input class="field-input" type="text" name="temperature" value="{{ old('temperature') }}" placeholder="e.g. 36.5°C">
                </div>
            </div>
        </div>

        {{-- ── CONFIRM & SUBMIT ── --}}
        <div class="confirm-row">
            <input type="checkbox" id="confirmAccuracy" required>
            <label for="confirmAccuracy">Confirm that the information provided is <strong>accurate and complete.</strong></label>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Generate Appointment</button>
            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary" style="text-decoration:none;display:inline-flex;align-items:center;">Cancel</a>
        </div>
    </form>
</div>

<script>
    // Date min enforcement
    document.addEventListener('DOMContentLoaded', function () {
        const d = document.getElementById('appointment_date');
        if (!d) return;
        const today = new Date();
        const min = today.getFullYear() + '-' + String(today.getMonth()+1).padStart(2,'0') + '-' + String(today.getDate()).padStart(2,'0');
        if (!d.getAttribute('min')) d.setAttribute('min', min);
        d.addEventListener('change', function () { if (this.value && this.value < min) this.value = min; });
    });

    // Photo preview
    document.getElementById('profile_photo')?.addEventListener('change', function () {
        const preview = document.getElementById('photoPreview');
        const [file] = this.files;
        if (!file) { preview.textContent = 'No photo'; return; }
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
        img.alt = 'Selected photo';
        img.onload = () => URL.revokeObjectURL(img.src);
        preview.replaceChildren(img);
    });

    // Pet/stray — treat as mutually exclusive checkbox
    document.querySelectorAll('[name="pet_or_stray"]').forEach(cb => {
        cb.addEventListener('change', function () {
            if (this.checked) {
                document.querySelectorAll('[name="pet_or_stray"]').forEach(o => { if (o !== this) o.checked = false; });
            }
        });
    });
</script>
@endsection
