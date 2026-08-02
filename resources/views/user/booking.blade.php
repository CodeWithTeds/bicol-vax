@extends('layouts.user')

@section('title', 'Booking Appointment')

@section('content')
    <style>
        .booking-wrap {
            max-width: 980px;
        }

        .booking-form {
            background: #e8f7f6;
            padding: 1.5rem;
            border-radius: 10px;
        }

        .section-title {
            background: #2b8f90;
            color: #fff;
            padding: 0.65rem 0.9rem;
            border-radius: 6px;
            font-weight: 700;
            margin: 1.25rem 0 0.9rem;
        }

        .section-title:first-of-type {
            margin-top: 0;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.9rem;
        }

        .form-group {
            margin-bottom: 0.9rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            color: #243b3b;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.7rem 0.8rem;
            border: 1px solid #c7dede;
            border-radius: 6px;
            background: #fff;
            font: inherit;
        }

        .photo-upload {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .photo-preview {
            width: 68px;
            height: 68px;
            display: grid;
            place-items: center;
            border: 1px dashed #8dbcbc;
            border-radius: 50%;
            background: #fff;
            color: #527272;
            font-size: 0.75rem;
            overflow: hidden;
            flex: 0 0 68px;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(120px, 1fr));
            gap: 0.5rem;
            margin-top: 0.4rem;
        }

        .checkbox-grid label {
            font-weight: 500;
        }

        .actions {
            margin-top: 1rem;
            display: flex;
            gap: 0.7rem;
            justify-content: flex-end;
        }

        .btn {
            border: 0;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: #2b8f90;
            color: #fff;
        }

        .btn-secondary {
            background: #d9e7e7;
            color: #1f2f2f;
        }

        @media (max-width: 900px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .checkbox-grid {
                grid-template-columns: repeat(2, minmax(120px, 1fr));
            }
        }
    </style>

    <div class="booking-wrap">
        <h1 style="margin-bottom: 1rem;">Online Booking Appointment</h1>
        <p style="margin-bottom: 0.9rem; color: #456;">Basic personal details are auto-filled from your account/profile.</p>

        @if (session('success'))
            <div style="margin-bottom: 1rem; padding: 0.85rem 1rem; border-radius: 6px; background: #e8f7ee; color: #1f6b38; border: 1px solid #bfe8cb;">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div style="margin-bottom: 1rem; padding: 0.85rem 1rem; border-radius: 6px; background: #fdecec; color: #9b1c1c; border: 1px solid #f5b5b5;">
                <strong>Please fix the following:</strong>
                <ul style="margin: 0.5rem 0 0 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="booking-form" method="POST" action="{{ route('user.booking.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="section-title">Patient Information</div>
            <div class="grid-2">
                <div class="form-group">
                    <label for="profile_photo">Profile Photo (optional)</label>
                    <div class="photo-upload">
                        <div class="photo-preview" id="photoPreview" aria-live="polite">
                            @if(!empty($patient?->profile_photo_path))
                                <img src="{{ '/storage/' . ltrim($patient->profile_photo_path, '/') }}" alt="Current profile photo">
                            @else
                                No photo
                            @endif
                        </div>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/jpeg,image/png,image/webp">
                    </div>
                    <small style="display: block; margin-top: 0.35rem; color: #527272;">Leave blank to use your saved profile photo. JPG, PNG, or WebP, maximum 2 MB.</small>
                </div>
                <div class="form-group">
                    <label>Preferred Appointment Date *</label>
                    <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label>Parent/Guardian Name (if minor)</label>
                    <input type="text" name="parent_guardian" value="{{ old('parent_guardian') }}">
                </div>
                <div class="form-group">
                    <label>Card No. (optional)</label>
                    <input type="text" name="card_no" value="{{ old('card_no') }}" placeholder="Auto-generated if left blank">
                </div>
                <div class="form-group">
                    <label>Case No. (optional)</label>
                    <input type="text" name="case_no" value="{{ old('case_no') }}" placeholder="Auto-generated if left blank">
                </div>
                
                <div class="form-group">
                    <label>CAT Category *</label>
                    <select name="cat_category" required>
                        <option value="">Select Category</option>
                        <option value="category_i" {{ old('cat_category') === 'category_i' ? 'selected' : '' }}>Category I</option>
                        <option value="category_ii" {{ old('cat_category') === 'category_ii' ? 'selected' : '' }}>Category II</option>
                        <option value="category_iii" {{ old('cat_category') === 'category_iii' ? 'selected' : '' }}>Category III</option>
                    </select>
                </div>
            </div>

            <div class="section-title">Treatment Required</div>
            <div class="checkbox-grid">
                <label><input type="checkbox" name="treatment[]" value="prprep" {{ in_array('prprep', old('treatment', [])) ? 'checked' : '' }}> PrPEP</label>
                <label><input type="checkbox" name="treatment[]" value="pep" {{ in_array('pep', old('treatment', [])) ? 'checked' : '' }}> PEP</label>
                <label><input type="checkbox" name="treatment[]" value="booster" {{ in_array('booster', old('treatment', [])) ? 'checked' : '' }}> Booster</label>
                <label><input type="checkbox" name="treatment[]" value="tet" {{ in_array('tet', old('treatment', [])) ? 'checked' : '' }}> TET</label>
                <label><input type="checkbox" name="treatment[]" value="erig" {{ in_array('erig', old('treatment', [])) ? 'checked' : '' }}> ERIG</label>
                <label><input type="checkbox" name="treatment[]" value="hrig" {{ in_array('hrig', old('treatment', [])) ? 'checked' : '' }}> HRIG</label>
            </div>

            <script>
                // Enforce min date for preferred appointment
                document.addEventListener('DOMContentLoaded', function() {
                    const dateInput = document.getElementById('appointment_date');
                    if (!dateInput) return;
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    const min = `${yyyy}-${mm}-${dd}`;
                    // Ensure min attribute matches server-side (fallback)
                    if (!dateInput.getAttribute('min')) dateInput.setAttribute('min', min);

                    dateInput.addEventListener('change', function() {
                        if (!this.value) return;
                        if (this.value < min) {
                            this.value = min;
                        }
                    });
                });
            </script>

            <script>
                document.getElementById('profile_photo')?.addEventListener('change', function () {
                    const preview = document.getElementById('photoPreview');
                    const [file] = this.files;

                    if (!file) {
                        preview.textContent = 'No photo';
                        return;
                    }

                    const image = document.createElement('img');
                    image.src = URL.createObjectURL(file);
                    image.alt = 'Selected profile photo preview';
                    image.onload = () => URL.revokeObjectURL(image.src);
                    preview.replaceChildren(image);
                });
            </script>

            <div class="section-title">Exposure History</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Bite Type</label>
                    <select name="bite_type">
                        <option value="">Select bite type</option>
                        <option value="scratch" {{ old('bite_type') === 'scratch' ? 'selected' : '' }}>Scratch</option>
                        <option value="bite" {{ old('bite_type') === 'bite' ? 'selected' : '' }}>Bite</option>
                        <option value="lick_broken_skin" {{ old('bite_type') === 'lick_broken_skin' ? 'selected' : '' }}>Lick on broken skin</option>
                        <option value="open_wound_exposure" {{ old('bite_type') === 'open_wound_exposure' ? 'selected' : '' }}>Open wounds exposure</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Place of Bite *</label>
                    <select name="place_of_bite" required>
                        <option value="">Select place of bite</option>
                        <option value="hand" {{ old('place_of_bite') === 'hand' ? 'selected' : '' }}>Hand</option>
                        <option value="arm" {{ old('place_of_bite') === 'arm' ? 'selected' : '' }}>Arm</option>
                        <option value="leg" {{ old('place_of_bite') === 'leg' ? 'selected' : '' }}>Leg</option>
                        <option value="foot" {{ old('place_of_bite') === 'foot' ? 'selected' : '' }}>Foot</option>
                        <option value="face" {{ old('place_of_bite') === 'face' ? 'selected' : '' }}>Face</option>
                        <option value="neck" {{ old('place_of_bite') === 'neck' ? 'selected' : '' }}>Neck</option>
                        <option value="finger" {{ old('place_of_bite') === 'finger' ? 'selected' : '' }}>Finger</option>
                        <option value="multiple_sites" {{ old('place_of_bite') === 'multiple_sites' ? 'selected' : '' }}>Multiple sites</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Source *</label>
                    <select name="source" required>
                        <option value="">Select source</option>
                        <option value="dog" {{ old('source') === 'dog' ? 'selected' : '' }}>Dog</option>
                        <option value="cat" {{ old('source') === 'cat' ? 'selected' : '' }}>Cat</option>
                        <option value="bat" {{ old('source') === 'bat' ? 'selected' : '' }}>Bat</option>
                        <option value="rat" {{ old('source') === 'rat' ? 'selected' : '' }}>Rat</option>
                        <option value="monkey" {{ old('source') === 'monkey' ? 'selected' : '' }}>Monkey</option>
                        <option value="other_animal" {{ old('source') === 'other_animal' ? 'selected' : '' }}>Other animal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Severity</label>
                    <select name="severity">
                        <option value="">Select severity</option>
                        <option value="mild" {{ old('severity') === 'mild' ? 'selected' : '' }}>Mild</option>
                        <option value="moderate" {{ old('severity') === 'moderate' ? 'selected' : '' }}>Moderate</option>
                        <option value="severe" {{ old('severity') === 'severe' ? 'selected' : '' }}>Severe</option>
                    </select>
                </div>
            </div>

            <div class="section-title">Anti-Rabies Vaccine</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Generic Name *</label>
                    <select name="generic_name" required>
                        <option value="">Select generic name</option>
                        <option value="purified_vero_cell" {{ old('generic_name') === 'purified_vero_cell' ? 'selected' : '' }}>Purified vero cell</option>
                        <option value="purified_chick_embryo" {{ old('generic_name') === 'purified_chick_embryo' ? 'selected' : '' }}>Purified chick embryo</option>
                        <option value="human_diploid" {{ old('generic_name') === 'human_diploid' ? 'selected' : '' }}>Human diploid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Route *</label>
                    <select name="route" required>
                        <option value="">Select route</option>
                        <option value="intramuscular" {{ old('route') === 'intramuscular' ? 'selected' : '' }}>Intramuscular</option>
                        <option value="intradermal" {{ old('route') === 'intradermal' ? 'selected' : '' }}>Intradermal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Brand Name *</label>
                    <select name="brand_name" required>
                        <option value="">Select brand</option>
                        <option value="verorab" {{ old('brand_name') === 'verorab' ? 'selected' : '' }}>Verorab</option>
                        <option value="speeda" {{ old('brand_name') === 'speeda' ? 'selected' : '' }}>Speeda</option>
                        <option value="rabiqur" {{ old('brand_name') === 'rabiqur' ? 'selected' : '' }}>Rabiqur</option>
                        <option value="abhayrab" {{ old('brand_name') === 'abhayrab' ? 'selected' : '' }}>Abhayrab</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dosage *</label>
                    <select name="dosage" required>
                        <option value="">Select dosage</option>
                        <option value="0_1ml" {{ old('dosage') === '0_1ml' ? 'selected' : '' }}>0.1 ml</option>
                        <option value="0_5ml" {{ old('dosage') === '0_5ml' ? 'selected' : '' }}>0.5 ml</option>
                        <option value="1_0ml" {{ old('dosage') === '1_0ml' ? 'selected' : '' }}>1.0 ml</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dose *</label>
                    <select name="anti_rabies_dose" required>
                        <option value="">Select dose</option>
                        <option value="day_0" {{ old('anti_rabies_dose') === 'day_0' ? 'selected' : '' }}>Day 0</option>
                        <option value="day_3" {{ old('anti_rabies_dose') === 'day_3' ? 'selected' : '' }}>Day 3</option>
                        <option value="day_7" {{ old('anti_rabies_dose') === 'day_7' ? 'selected' : '' }}>Day 7</option>
                        <option value="day_14" {{ old('anti_rabies_dose') === 'day_14' ? 'selected' : '' }}>Day 14</option>
                        <option value="day_28" {{ old('anti_rabies_dose') === 'day_28' ? 'selected' : '' }}>Day 28</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Anti-Rabies Date *</label>
                    <input type="date" name="anti_rabies_date" value="{{ old('anti_rabies_date') }}" required>
                </div>
            </div>

            <div class="section-title">Tetanus / Immunoglobulin</div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Tetanus Status *</label>
                    <select name="tetanus_status" required>
                        <option value="">Select status</option>
                        <option value="valid" {{ old('tetanus_status') === 'valid' ? 'selected' : '' }}>Valid</option>
                        <option value="expired" {{ old('tetanus_status') === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="unknown" {{ old('tetanus_status') === 'unknown' ? 'selected' : '' }}>Unknown</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tetanus Dose *</label>
                    <select name="tetanus_dose" required>
                        <option value="">Select dose</option>
                        <option value="dose1" {{ old('tetanus_dose') === 'dose1' ? 'selected' : '' }}>Dose 1</option>
                        <option value="dose2" {{ old('tetanus_dose') === 'dose2' ? 'selected' : '' }}>Dose 2</option>
                        <option value="dose3" {{ old('tetanus_dose') === 'dose3' ? 'selected' : '' }}>Dose 3</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tetanus Date *</label>
                    <input type="date" name="tetanus_date" value="{{ old('tetanus_date') }}" required>
                </div>
                <div class="form-group">
                    <label>Rabies Immunoglobulin *</label>
                    <select name="rabies_immunoglobulin" required>
                        <option value="">Select type</option>
                        <option value="erig" {{ old('rabies_immunoglobulin') === 'erig' ? 'selected' : '' }}>ERIG</option>
                        <option value="hrig" {{ old('rabies_immunoglobulin') === 'hrig' ? 'selected' : '' }}>HRIG</option>
                        <option value="none" {{ old('rabies_immunoglobulin') === 'none' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">Submit Booking</button>
            </div>
        </form>
    </div>
@endsection
