@extends('layouts.admin')

@section('title', 'Patients')

@section('content')
    <style>
        /* Tabs */
        .tab-btn {
            border: 1px solid #d9dee8;
            background: #fff;
            color: #445066;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 700;
            transition: all 160ms ease;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #2b8f90 0%, #42d4de 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 8px 20px rgba(43, 143, 144, 0.12);
        }

        /* Table improvements */
        table { width: 100%; border-collapse: collapse; background: #fff; }
        thead th { background: #f6fbfb; color: #234; font-weight: 700; padding: 0.85rem; border-bottom: 1px solid #e6eef0; }
        tbody td { padding: 0.85rem; border-bottom: 1px solid #f1f5f6; }
        tbody tr:nth-child(even) { background: #fbfdfd; }

        /* Action buttons */
        .action-buttons .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            line-height: 1;
            text-decoration: none;
            transition: transform 160ms ease, opacity 160ms ease, box-shadow 160ms ease;
        }
        .action-buttons .icon-btn:hover { transform: translateY(-1px); opacity: 0.88; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .action-buttons .icon-btn svg { display: block; }
        .icon-view { background: #e0f4f4; color: #2b8f90; }
        .icon-edit { background: #fff3e0; color: #e8890c; }
        .icon-approve { background: #d4edda; color: #1f8a4c; }

        /* Status badges */
        .badge-approved { background: #d4edda; color: #155724; padding: 0.25rem 0.6rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600; }
        .badge-not_approved { background: #fff3cd; color: #856404; padding: 0.25rem 0.6rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600; }
        .badge-rejected { background: #f8d7da; color: #721c24; padding: 0.25rem 0.6rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600; }

        /* Status filter */
        .status-filter {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .status-filter label {
            font-weight: 600;
            color: #243b3b;
            font-size: 0.9rem;
        }

        .status-filter select {
            padding: 0.4rem 0.8rem;
            border: 1px solid #c7dede;
            border-radius: 6px;
            font-size: 0.9rem;
            background: #fff;
        }

        /* Responsive tweaks */
        @media (max-width: 900px) {
            .detail-card { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }
        .patients-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .patients-header h1 {
            font-size: 1.8rem;
            color: #2b8f90;
        }

        .stat-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card-small {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-top: 4px solid #2b8f90;
            cursor: pointer;
        }

        .stat-card-small .label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .stat-card-small .value {
            font-size: 2rem;
            font-weight: 700;
            color: #2b8f90;
        }

        .search-container {
            margin-bottom: 1.5rem;
        }

        .search-container input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h2 {
            color: #2b8f90;
            font-size: 1.5rem;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
        }

        .modal-close:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
            font-family: inherit;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .modal-footer button {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .btn-cancel {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-submit {
            background-color: #50c878;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .detail-item {
            padding: 0.85rem 1rem;
            background: #f8f9fa;
            border: 1px solid #e8ecef;
            border-radius: 8px;
        }

        .detail-label {
            display: block;
            font-size: 0.78rem;
            color: #6c757d;
            margin-bottom: 0.3rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .detail-value {
            font-size: 0.96rem;
            color: #222;
            font-weight: 600;
            word-break: break-word;
        }

        .detail-section {
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #2b8f90;
        }

        .section-icon {
            font-size: 1.5rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2b8f90;
        }

        .detail-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafb 100%);
            border: 1px solid #e0e7ff;
            border-radius: 12px;
            padding: 1.25rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .detail-field {
            display: flex;
            flex-direction: column;
        }

        .field-label {
            font-size: 0.75rem;
            color: #7c8aa7;
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .field-value {
            font-size: 0.98rem;
            color: #1a1e3f;
            font-weight: 600;
            word-break: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            display: block;
        }

        .field-value.highlight {
            color: #2b8f90;
            background: #e8f7ee;
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            display: inline-block;
            width: fit-content;
        }

        .view-modal-scroll {
            max-height: calc(90vh - 150px);
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .view-modal-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .view-modal-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .view-modal-scroll::-webkit-scrollbar-thumb {
            background: #2b8f90;
            border-radius: 10px;
        }

        .success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 3000;
            align-items: center;
            justify-content: center;
        }

        .success-modal.active {
            display: flex;
        }

        .success-content {
            background: white;
            border-radius: 12px;
            padding: 3rem 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .success-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #50c878;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }
    </style>

    <div class="patients-header">
        <h1>Patients Management</h1>
        <div style="display:flex; gap:0.75rem; align-items:center;">
            <div style="display:flex; gap:0.5rem;">
                <button class="tab-btn active" id="tabAll" onclick="showPatientsTab('all')">All</button>
                <button class="tab-btn" id="tabWalkin" onclick="showPatientsTab('walkin')">Walk-ins</button>
                <button class="tab-btn" id="tabOnline" onclick="showPatientsTab('online')">Online Registrations (Approved)</button>
            </div>
            <button class="btn btn-primary" onclick="openAddPatientModal()">Add Patient</button>
        </div>
    </div>

    @if (session('success'))
        <div style="margin-bottom: 1rem; padding: 0.85rem 1rem; border-radius: 6px; background: #e8f7ee; color: #1f6b38; border: 1px solid #bfe8cb;">
            {{ session('success') }}
        </div>
    @endif

    <div class="stat-cards-container">
        <div class="stat-card-small" onclick="statCardClick('patients', this)">
            <div class="label">Total Patients</div>
            <div class="value">{{ $totalPatients ?? $patients->count() }}</div>
        </div>
        <div class="stat-card-small" onclick="statCardClick('appointments', this)">
            <div class="label">Total Appointments</div>
            <div class="value">{{ $appointments->count() ?? 0 }}</div>
        </div>
        <div class="stat-card-small" onclick="statCardClick('ongoing', this)">
            <div class="label">Ongoing Appointments</div>
            <div class="value">{{ $ongoingAppointments->count() ?? 0 }}</div>
        </div>
        <div class="stat-card-small" onclick="statCardClick('missed', this)">
            <div class="label">Missed Appointments</div>
            <div class="value">{{ $missedAppointments->count() ?? 0 }}</div>
        </div>
        <div class="stat-card-small" onclick="statCardClick('completed', this)">
            <div class="label">Completed Appointments</div>
            <div class="value">{{ $completedAppointments->count() ?? 0 }}</div>
        </div>
    </div>

    <div class="search-container">
        <div class="status-filter">
            <label for="statusFilter">Filter by Status:</label>
            <select id="statusFilter" onchange="filterByStatus()">
                <option value="all">All Statuses</option>
                <option value="approved">Approved</option>
                <option value="not_approved">Pending</option>
            </select>
        </div>
        <input type="text" placeholder="Search Patients..." id="searchInput">
    </div>

    <div class="content-card">
        <div id="aptToggleButtons" style="margin-bottom:1rem; display:flex; gap:0.5rem; align-items:center;">
            <button class="tab-btn" onclick="showAptSection('ongoing', this)">Show Ongoing</button>
            <button class="tab-btn" onclick="showAptSection('missed', this)">Show Missed</button>
            <button class="tab-btn" onclick="showAptSection('completed', this)">Show Completed</button>
            <button class="tab-btn active" onclick="showAptSection('none', this)">Hide</button>
        </div>

        <div id="ongoingAppointments" style="display:none; margin-bottom:1rem;">
            <h3 style="margin:0 0 0.5rem 0;">Ongoing Appointments</h3>
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Appointment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ongoingAppointments as $apt)
                        <tr>
                            <td>{{ $apt->full_name }}</td>
                            <td>{{ $apt->contact }}</td>
                            <td>{{ optional($apt->appointment_date)->format('Y-m-d') }}</td>
                            <td>{{ $apt->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:#999;padding:1rem;">No ongoing appointments</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="missedAppointments" style="display:none; margin-bottom:1rem;">
            <h3 style="margin:0 0 0.5rem 0;">Missed Appointments</h3>
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Appointment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($missedAppointments as $apt)
                        <tr>
                            <td>{{ $apt->full_name }}</td>
                            <td>{{ $apt->contact }}</td>
                            <td>{{ optional($apt->appointment_date)->format('Y-m-d') }}</td>
                            <td>{{ $apt->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:#999;padding:1rem;">No missed appointments</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="completedAppointments" style="display:none; margin-bottom:1rem;">
            <h3 style="margin:0 0 0.5rem 0;">Completed Appointments</h3>
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Appointment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completedAppointments as $apt)
                        <tr>
                            <td>{{ $apt->full_name }}</td>
                            <td>{{ $apt->contact }}</td>
                            <td>{{ optional($apt->appointment_date)->format('Y-m-d') }}</td>
                            <td>{{ $apt->status }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center;color:#999;padding:1rem;">No completed appointments</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-wrap">
            <table id="allTable" style="margin-top: 1rem;">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Case No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Date of Registration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($allPatients as $patient)
                        <tr data-status="{{ $patient->status ?? 'not_approved' }}">
                            <td>{{ (($patient->source ?? null) === 'web' || str_starts_with($patient->card_no, 'WEB-')) ? 'Online Registration' : 'Walk-in' }}</td>
                            <td>{{ $patient->case_no }}</td>
                            <td>{{ $patient->full_name }}</td>
                            <td>{{ $patient->email ?? '-' }}</td>
                            <td>{{ $patient->contact }}</td>
                            <td>{{ optional($patient->created_at)->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge-{{ $patient->status ?? 'not_approved' }}">
                                    {{ $patient->status === 'approved' ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button
                                        type="button"
                                        class="btn-view icon-btn icon-view"
                                        title="View patient details"
                                        aria-label="View patient details"
                                        data-full-name="{{ e($patient->full_name) }}"
                                        data-card-no="{{ e($patient->card_no) }}"
                                        data-case-no="{{ e($patient->case_no) }}"
                                        data-contact="{{ e($patient->contact) }}"
                                        data-age="{{ e($patient->age) }}"
                                        data-email="{{ e($patient->email) }}"
                                        data-gender="{{ e($patient->gender) }}"
                                        data-address="{{ e($patient->address) }}"
                                        data-weight="{{ e($patient->weight) }}"
                                        data-cat-category="{{ e($patient->cat_category) }}"
                                        data-treatment-required="{{ e(is_array($patient->treatment_required) ? implode(',', $patient->treatment_required) : '') }}"
                                        data-bite-type="{{ e($patient->bite_type) }}"
                                        data-place-of-bite="{{ e($patient->place_of_bite) }}"
                                        data-source="{{ e($patient->source) }}"
                                        data-severity="{{ e($patient->severity) }}"
                                        data-generic-name="{{ e($patient->generic_name) }}"
                                        data-route="{{ e($patient->route) }}"
                                        data-brand-name="{{ e($patient->brand_name) }}"
                                        data-dosage="{{ e($patient->dosage) }}"
                                        data-anti-rabies-dose="{{ e($patient->anti_rabies_dose) }}"
                                        data-anti-rabies-date="{{ e(optional($patient->anti_rabies_date)->format('Y-m-d')) }}"
                                        data-tetanus-status="{{ e($patient->tetanus_status) }}"
                                        data-tetanus-dose="{{ e($patient->tetanus_dose) }}"
                                        data-tetanus-date="{{ e(optional($patient->tetanus_date)->format('Y-m-d')) }}"
                                        data-rabies-immunoglobulin="{{ e($patient->rabies_immunoglobulin) }}"
                                        data-birthday="{{ e(optional($patient->birthday)->format('Y-m-d')) }}"
                                        data-blood-pressure="{{ e($patient->blood_pressure) }}"
                                        data-temperature="{{ e($patient->temperature) }}"
                                        data-animal-type="{{ e($patient->animal_type) }}"
                                        data-pet-or-stray="{{ e($patient->pet_or_stray) }}"
                                        data-vaccinated-animal="{{ e($patient->vaccinated_animal) }}"
                                        data-animal-status="{{ e($patient->animal_status) }}"
                                        data-date-of-bite="{{ e(optional($patient->date_of_bite)->format('Y-m-d')) }}"
                                        data-washing-of-wound="{{ e($patient->washing_of_wound) }}"
                                        data-tandok-tambal="{{ e($patient->tandok_tambal) }}"
                                        data-owner-name="{{ e($patient->owner_name) }}"
                                        data-owner-address="{{ e($patient->owner_address) }}"
                                        data-has-diabetes="{{ e($patient->has_diabetes ? '1' : '0') }}"
                                        data-has-cancer="{{ e($patient->has_cancer ? '1' : '0') }}"
                                        data-has-organ-transplant="{{ e($patient->has_organ_transplant ? '1' : '0') }}"
                                        data-has-ckd="{{ e($patient->has_ckd ? '1' : '0') }}"
                                        data-has-hiv="{{ e($patient->has_hiv ? '1' : '0') }}"
                                        data-taking-steroid="{{ e($patient->taking_steroid ? '1' : '0') }}"
                                        data-has-riv="{{ e($patient->has_riv ? '1' : '0') }}"
                                        data-allergy="{{ e($patient->allergy) }}"
                                        data-created-at="{{ e(optional($patient->created_at)->format('Y-m-d H:i')) }}"
                                        onclick='openViewPatientModalFromButton(this)'
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn-edit icon-btn icon-edit"
                                        title="Edit patient information and treatment"
                                        aria-label="Edit patient information and treatment"
                                        data-update-url="{{ route('admin.patients.update', $patient) }}"
                                        onclick='openEditPatientModalFromButton(this)'
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                    </button>
                                    @if ($patient->status !== 'approved')
                                        <button
                                            type="button"
                                            class="icon-btn icon-approve"
                                            title="Approve patient"
                                            aria-label="Approve patient"
                                            onclick="togglePatientStatus({{ $patient->id }}, 'approved', this)"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; color:#999; padding:2rem;">No records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <table id="walkinTable" style="display:none;">
                <thead>
                    <tr>
                        <th>Case No.</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Dose</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="patientsTableBody">
                    @forelse ($patients as $patient)
                        <tr data-status="{{ $patient->status ?? 'not_approved' }}">
                            <td>{{ $patient->case_no }}</td>
                            <td>{{ $patient->full_name }}</td>
                            <td>{{ $patient->age }}</td>
                            <td>{{ ucfirst($patient->gender) }}</td>
                            <td>{{ $patient->contact }}</td>
                            <td>{{ $patient->address }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $patient->anti_rabies_dose)) }}</td>
                            <td>
                                <span class="badge-{{ $patient->status ?? 'not_approved' }}">
                                    {{ $patient->status === 'approved' ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button
                                        type="button"
                                        class="btn-view icon-btn icon-view"
                                        title="View patient details"
                                        aria-label="View patient details"
                                        data-full-name="{{ e($patient->full_name) }}"
                                        data-card-no="{{ e($patient->card_no) }}"
                                        data-case-no="{{ e($patient->case_no) }}"
                                        data-contact="{{ e($patient->contact) }}"
                                        data-age="{{ e($patient->age) }}"
                                        data-email="{{ e($patient->email) }}"
                                        data-gender="{{ e($patient->gender) }}"
                                        data-address="{{ e($patient->address) }}"
                                        data-weight="{{ e($patient->weight) }}"
                                        data-cat-category="{{ e($patient->cat_category) }}"
                                        data-treatment-required="{{ e(is_array($patient->treatment_required) ? implode(',', $patient->treatment_required) : '') }}"
                                        data-bite-type="{{ e($patient->bite_type) }}"
                                        data-place-of-bite="{{ e($patient->place_of_bite) }}"
                                        data-source="{{ e($patient->source) }}"
                                        data-severity="{{ e($patient->severity) }}"
                                        data-generic-name="{{ e($patient->generic_name) }}"
                                        data-route="{{ e($patient->route) }}"
                                        data-brand-name="{{ e($patient->brand_name) }}"
                                        data-dosage="{{ e($patient->dosage) }}"
                                        data-anti-rabies-dose="{{ e($patient->anti_rabies_dose) }}"
                                        data-anti-rabies-date="{{ e(optional($patient->anti_rabies_date)->format('Y-m-d')) }}"
                                        data-tetanus-status="{{ e($patient->tetanus_status) }}"
                                        data-tetanus-dose="{{ e($patient->tetanus_dose) }}"
                                        data-tetanus-date="{{ e(optional($patient->tetanus_date)->format('Y-m-d')) }}"
                                        data-rabies-immunoglobulin="{{ e($patient->rabies_immunoglobulin) }}"
                                        data-birthday="{{ e(optional($patient->birthday)->format('Y-m-d')) }}"
                                        data-blood-pressure="{{ e($patient->blood_pressure) }}"
                                        data-temperature="{{ e($patient->temperature) }}"
                                        data-animal-type="{{ e($patient->animal_type) }}"
                                        data-pet-or-stray="{{ e($patient->pet_or_stray) }}"
                                        data-vaccinated-animal="{{ e($patient->vaccinated_animal) }}"
                                        data-animal-status="{{ e($patient->animal_status) }}"
                                        data-date-of-bite="{{ e(optional($patient->date_of_bite)->format('Y-m-d')) }}"
                                        data-washing-of-wound="{{ e($patient->washing_of_wound) }}"
                                        data-tandok-tambal="{{ e($patient->tandok_tambal) }}"
                                        data-owner-name="{{ e($patient->owner_name) }}"
                                        data-owner-address="{{ e($patient->owner_address) }}"
                                        data-has-diabetes="{{ e($patient->has_diabetes ? '1' : '0') }}"
                                        data-has-cancer="{{ e($patient->has_cancer ? '1' : '0') }}"
                                        data-has-organ-transplant="{{ e($patient->has_organ_transplant ? '1' : '0') }}"
                                        data-has-ckd="{{ e($patient->has_ckd ? '1' : '0') }}"
                                        data-has-hiv="{{ e($patient->has_hiv ? '1' : '0') }}"
                                        data-taking-steroid="{{ e($patient->taking_steroid ? '1' : '0') }}"
                                        data-has-riv="{{ e($patient->has_riv ? '1' : '0') }}"
                                        data-allergy="{{ e($patient->allergy) }}"
                                        data-created-at="{{ e(optional($patient->created_at)->format('Y-m-d H:i')) }}"
                                        onclick='openViewPatientModalFromButton(this)'
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn-edit icon-btn icon-edit"
                                        title="Edit patient information and treatment"
                                        aria-label="Edit patient information and treatment"
                                        data-update-url="{{ route('admin.patients.update', $patient) }}"
                                        onclick='openEditPatientModalFromButton(this)'
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                    </button>
                                    @if ($patient->status !== 'approved')
                                        <button
                                            type="button"
                                            class="icon-btn icon-approve"
                                            title="Approve patient"
                                            aria-label="Approve patient"
                                            onclick="togglePatientStatus({{ $patient->id }}, 'approved', this)"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; color: #999; padding: 2rem;">No Patient recorded</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <table id="onlineTable" style="display:none; margin-top: 1rem;">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Date of Registration</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($approvedOnlineRegistrations as $apt)
                        <tr>
                            <td>{{ $apt->full_name }}</td>
                            <td>{{ $apt->email ?? '-' }}</td>
                            <td>{{ $apt->contact }}</td>
                            <td>{{ optional($apt->created_at)->format('Y-m-d') }}</td>
                            <td id="patient-status-{{ $apt->id }}">
                                <span class="badge-{{ $apt->status ?? 'not_approved' }}">
                                    {{ $apt->status === 'approved' ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button
                                        type="button"
                                        class="btn-view icon-btn icon-view"
                                        title="View patient details"
                                        aria-label="View patient details"
                                        data-full-name="{{ e($apt->full_name) }}"
                                        data-card-no="{{ e($apt->card_no) }}"
                                        data-case-no="{{ e($apt->case_no) }}"
                                        data-contact="{{ e($apt->contact) }}"
                                        data-age="{{ e($apt->age) }}"
                                        data-email="{{ e($apt->email) }}"
                                        data-gender="{{ e($apt->gender) }}"
                                        data-address="{{ e($apt->address) }}"
                                        data-weight="{{ e($apt->weight) }}"
                                        data-cat-category="{{ e($apt->cat_category) }}"
                                        data-treatment-required="{{ e(is_array($apt->treatment_required) ? implode(',', $apt->treatment_required) : '') }}"
                                        data-bite-type="{{ e($apt->bite_type) }}"
                                        data-place-of-bite="{{ e($apt->place_of_bite) }}"
                                        data-source="{{ e($apt->source) }}"
                                        data-severity="{{ e($apt->severity) }}"
                                        data-generic-name="{{ e($apt->generic_name) }}"
                                        data-route="{{ e($apt->route) }}"
                                        data-brand-name="{{ e($apt->brand_name) }}"
                                        data-dosage="{{ e($apt->dosage) }}"
                                        data-anti-rabies-dose="{{ e($apt->anti_rabies_dose) }}"
                                        data-anti-rabies-date="{{ e(optional($apt->anti_rabies_date)->format('Y-m-d')) }}"
                                        data-tetanus-status="{{ e($apt->tetanus_status) }}"
                                        data-tetanus-dose="{{ e($apt->tetanus_dose) }}"
                                        data-tetanus-date="{{ e(optional($apt->tetanus_date)->format('Y-m-d')) }}"
                                        data-rabies-immunoglobulin="{{ e($apt->rabies_immunoglobulin) }}"
                                        data-birthday="{{ e(optional($apt->birthday)->format('Y-m-d')) }}"
                                        data-blood-pressure="{{ e($apt->blood_pressure) }}"
                                        data-temperature="{{ e($apt->temperature) }}"
                                        data-animal-type="{{ e($apt->animal_type) }}"
                                        data-pet-or-stray="{{ e($apt->pet_or_stray) }}"
                                        data-vaccinated-animal="{{ e($apt->vaccinated_animal) }}"
                                        data-animal-status="{{ e($apt->animal_status) }}"
                                        data-date-of-bite="{{ e(optional($apt->date_of_bite)->format('Y-m-d')) }}"
                                        data-washing-of-wound="{{ e($apt->washing_of_wound) }}"
                                        data-tandok-tambal="{{ e($apt->tandok_tambal) }}"
                                        data-owner-name="{{ e($apt->owner_name) }}"
                                        data-owner-address="{{ e($apt->owner_address) }}"
                                        data-has-diabetes="{{ e($apt->has_diabetes ? '1' : '0') }}"
                                        data-has-cancer="{{ e($apt->has_cancer ? '1' : '0') }}"
                                        data-has-organ-transplant="{{ e($apt->has_organ_transplant ? '1' : '0') }}"
                                        data-has-ckd="{{ e($apt->has_ckd ? '1' : '0') }}"
                                        data-has-hiv="{{ e($apt->has_hiv ? '1' : '0') }}"
                                        data-taking-steroid="{{ e($apt->taking_steroid ? '1' : '0') }}"
                                        data-has-riv="{{ e($apt->has_riv ? '1' : '0') }}"
                                        data-allergy="{{ e($apt->allergy) }}"
                                        data-created-at="{{ e(optional($apt->created_at)->format('Y-m-d H:i')) }}"
                                        onclick='openViewPatientModalFromButton(this)'
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn-edit icon-btn icon-edit"
                                        title="Edit patient information and treatment"
                                        aria-label="Edit patient information and treatment"
                                        data-update-url="{{ route('admin.patients.update', $apt) }}"
                                        onclick='openEditPatientModalFromButton(this)'
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center; color:#999; padding:2rem;">No approved online registrations</td></tr>
                    @endforelse
                </tbody>
            </table>
                <div id="searchNoResults" style="display:none; text-align:center; color:#999; padding:1rem;">No matching patients</div>
        </div>
    </div>

    <script>
        function showPatientsTab(tab) {
            const all = document.getElementById('allTable');
            const walkin = document.getElementById('walkinTable');
            const online = document.getElementById('onlineTable');
            const tabAll = document.getElementById('tabAll');
            const tabWalkin = document.getElementById('tabWalkin');
            const tabOnline = document.getElementById('tabOnline');

            if (tab === 'all') {
                all.style.display = 'table';
                walkin.style.display = 'none';
                online.style.display = 'none';
                tabAll.classList.add('active');
                tabWalkin.classList.remove('active');
                tabOnline.classList.remove('active');
            } else if (tab === 'online') {
                all.style.display = 'none';
                walkin.style.display = 'none';
                online.style.display = 'table';
                tabAll.classList.remove('active');
                tabWalkin.classList.remove('active');
                tabOnline.classList.add('active');
            } else {
                all.style.display = 'none';
                walkin.style.display = 'table';
                online.style.display = 'none';
                tabAll.classList.remove('active');
                tabWalkin.classList.add('active');
                tabOnline.classList.remove('active');
            }
        }

        function showAptSection(section, btn) {
            const ongoing = document.getElementById('ongoingAppointments');
            const missed = document.getElementById('missedAppointments');
            const completed = document.getElementById('completedAppointments');

            // Toggle logic: if clicking the same visible section, hide all
            const isOngoingVisible = ongoing.style.display === 'block';
            const isMissedVisible = missed.style.display === 'block';
            const isCompletedVisible = completed.style.display === 'block';

            // Hide all first
            ongoing.style.display = 'none';
            missed.style.display = 'none';
            completed.style.display = 'none';

            if (section === 'ongoing') {
                if (!isOngoingVisible) ongoing.style.display = 'block';
            } else if (section === 'missed') {
                if (!isMissedVisible) missed.style.display = 'block';
            } else if (section === 'completed') {
                if (!isCompletedVisible) completed.style.display = 'block';
            }

            // Update active button state
            if (btn) {
                const parent = document.getElementById('aptToggleButtons');
                parent.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            }
        }

        function statCardClick(type, btn) {
            // Ensure we're on the 'All' tab for appointment-related sections
            if (type === 'patients') {
                showPatientsTab('all');
                // scroll to all table
                document.getElementById('allTable').scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }

            // For appointments, switch to All and show specific section
            showPatientsTab('all');

            if (type === 'appointments') {
                // scroll to top of appointments toggles
                const top = document.getElementById('ongoingAppointments') || document.querySelector('.content-card');
                (top || document.body).scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }

            if (type === 'ongoing') {
                showAptSection('ongoing');
                document.getElementById('ongoingAppointments').scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }

            if (type === 'missed') {
                showAptSection('missed');
                document.getElementById('missedAppointments').scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }

            if (type === 'completed') {
                showAptSection('completed');
                document.getElementById('completedAppointments').scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }
        }

        // Global search/filter patients in the currently visible table(s) - handles both search and status filter
        function filterPatients() {
            const q = (document.getElementById('searchInput').value || '').trim().toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const tableIds = ['allTable', 'walkinTable', 'onlineTable'];

            let visibleTables = tableIds
                .map(id => document.getElementById(id))
                .filter(t => t && t.style.display !== 'none');
            if (visibleTables.length === 0) {
                const walk = document.getElementById('walkinTable');
                if (walk) visibleTables = [walk];
            }

            let totalMatches = 0;
            visibleTables.forEach(table => {
                const tbody = table.tBodies && table.tBodies[0];
                if (!tbody) return;
                Array.from(tbody.rows).forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const rowStatus = row.dataset.status || '';
                    const matchesSearch = q === '' || text.indexOf(q) !== -1;
                    const matchesStatus = statusFilter === 'all' || rowStatus === statusFilter;
                    const match = matchesSearch && matchesStatus;
                    row.style.display = match ? '' : 'none';
                    if (match) totalMatches++;
                });
            });

            const noRes = document.getElementById('searchNoResults');
            if (noRes) noRes.style.display = totalMatches === 0 ? 'block' : 'none';
        }

        document.getElementById('searchInput')?.addEventListener('input', filterPatients);
    </script>

    <div class="modal {{ $errors->any() ? 'active' : '' }}" id="addPatientModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Register New</h2>
                <button class="modal-close" type="button" onclick="closeAddPatientModal()">×</button>
            </div>
            <p style="color: #666; margin-bottom: 1.5rem; font-size: 0.95rem;">Complete the form to register and schedule vaccination</p>

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

            <form id="addPatientForm" method="POST" action="{{ route('admin.patients.store') }}">
                @csrf
                <div class="form-step" id="step1">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Enter full name">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Card No. *</label>
                            <input type="text" name="card_no" value="{{ old('card_no') }}" placeholder="Card number">
                        </div>
                        <div class="form-group">
                            <label>Case No. *</label>
                            <input type="text" name="case_no" value="{{ old('case_no') }}" placeholder="Case number">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Contact *</label>
                            <input type="tel" name="contact" value="{{ old('contact') }}" placeholder="Phone number">
                        </div>
                        <div class="form-group">
                            <label>Age *</label>
                            <input type="number" name="age" value="{{ old('age') }}" placeholder="Age">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address">
                        </div>
                        <div class="form-group">
                            <label>Gender *</label>
                            <select name="gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address *</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="Street address">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Weight *</label>
                            <input type="number" name="weight" value="{{ old('weight') }}" placeholder="Weight (kg)">
                        </div>
                        <div class="form-group">
                            <label>CAT (Category of exposure) *</label>
                            <select name="cat_category">
                                <option value="">Select Category</option>
                                <option value="category_i">Category I</option>
                                <option value="category_ii">Category II</option>
                                <option value="category_iii">Category III</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeAddPatientModal()">Back</button>
                        <button type="button" class="btn-submit" onclick="goToStep(2)">Next</button>
                    </div>
                </div>

                <div class="form-step" id="step2" style="display: none;">
                    <div class="form-group">
                        <label style="font-weight: 700; margin-bottom: 0.8rem; display: block;">Treatment Required</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" id="prprep" name="treatment[]" value="prprep"><label for="prprep" style="margin: 0; font-weight: normal;">PrPEP</label></div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" id="pep" name="treatment[]" value="pep"><label for="pep" style="margin: 0; font-weight: normal;">PEP</label></div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" id="booster" name="treatment[]" value="booster"><label for="booster" style="margin: 0; font-weight: normal;">Booster</label></div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" id="tet" name="treatment[]" value="tet"><label for="tet" style="margin: 0; font-weight: normal;">TET</label></div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" id="erig" name="treatment[]" value="erig"><label for="erig" style="margin: 0; font-weight: normal;">ERIG</label></div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;"><input type="checkbox" id="hrig" name="treatment[]" value="hrig"><label for="hrig" style="margin: 0; font-weight: normal;">HRIG</label></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 700; margin-bottom: 0.8rem; display: block;">Exposure History</label>
                        <div class="form-row" style="margin-bottom: 1rem;">
                            <div class="form-group">
                                <label>Bite</label>
                                <select name="bite_type">
                                    <option value="">Select bite type</option>
                                    <option value="scratch">Scratch</option>
                                    <option value="bite">Bite</option>
                                    <option value="lick_broken_skin">Lick on broken skin</option>
                                    <option value="open_wound_exposure">Open wounds exposure</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Place of Bite *</label>
                                <select name="place_of_bite">
                                    <option value="">Select place of bite</option>
                                    <option value="hand">Hand</option>
                                    <option value="arm">Arm</option>
                                    <option value="leg">Leg</option>
                                    <option value="foot">Foot</option>
                                    <option value="face">Face</option>
                                    <option value="neck">Neck</option>
                                    <option value="finger">Finger</option>
                                    <option value="multiple_sites">Multiple sites</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Source *</label>
                                <select name="source">
                                    <option value="">Select source</option>
                                    <option value="dog">Dog</option>
                                    <option value="cat">Cat</option>
                                    <option value="bat">Bat</option>
                                    <option value="rat">Rat</option>
                                    <option value="monkey">Monkey</option>
                                    <option value="other_animal">Other animal</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Severity</label>
                                <select name="severity">
                                    <option value="">Select severity</option>
                                    <option value="mild">Mild</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="severe">Severe</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 700; margin-bottom: 0.8rem; display: block;">Anti-Rabies Vaccine</label>
                        <div class="form-row" style="margin-bottom: 1rem;">
                            <div class="form-group">
                                <label>Generic Name *</label>
                                <select name="generic_name">
                                    <option value="">Select generic name</option>
                                    <option value="purified_vero_cell">Purified vero cell rabies vaccine</option>
                                    <option value="purified_chick_embryo">Purified chick embryo cell vaccine</option>
                                    <option value="human_diploid">Human diploid cell vaccine</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Route *</label>
                                <select name="route">
                                    <option value="">Select route</option>
                                    <option value="intramuscular">Intramuscular</option>
                                    <option value="intradermal">Intradermal</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row" style="margin-bottom: 1rem;">
                            <div class="form-group">
                                <label>Brand Name *</label>
                                <select name="brand_name">
                                    <option value="">Select brand</option>
                                    <option value="verorab">Verorab</option>
                                    <option value="speeda">Speeda</option>
                                    <option value="rabiqur">Rabiqur</option>
                                    <option value="abhayrab">Abhayrab</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Dosage *</label>
                                <select name="dosage">
                                    <option value="">Select dosage</option>
                                    <option value="0_1ml">0.1 ml</option>
                                    <option value="0_5ml">0.5 ml</option>
                                    <option value="1_0ml">1.0 ml</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Dose *</label>
                                <select name="anti_rabies_dose">
                                    <option value="">Select dose</option>
                                    <option value="day_0">Day 0</option>
                                    <option value="day_3">Day 3</option>
                                    <option value="day_7">Day 7</option>
                                    <option value="day_14">Day 14</option>
                                    <option value="day_28">Day 28</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" onclick="goToStep(1)">Back</button>
                        <button type="button" class="btn-submit" onclick="goToStep(3)">Next</button>
                    </div>
                </div>

                <div class="form-step" id="step3" style="display: none;">
                    <div class="form-group">
                        <label style="font-weight: 700; margin-bottom: 1rem; display: block;">Tetanus Toxoid</label>
                        <select name="tetanus_status">
                            <option value="">Select tetanus toxoid status</option>
                            <option value="valid">Valid</option>
                            <option value="expired">Expired</option>
                            <option value="unknown">Unknown</option>
                        </select>
                    </div>

                    <div class="form-row" style="margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label>Dose *</label>
                            <select name="tetanus_dose">
                                <option value="">Select dose</option>
                                <option value="dose1">Dose 1</option>
                                <option value="dose2">Dose 2</option>
                                <option value="dose3">Dose 3</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 700; margin-bottom: 1rem; display: block;">Rabies ImmunoGlobulin</label>
                        <select name="rabies_immunoglobulin">
                            <option value="">Select immunoglobulin type</option>
                            <option value="erig">ERIG</option>
                            <option value="hrig">HRIG</option>
                            <option value="none">None</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn-submit" style="width: 100%; justify-content: center; gap: 0.5rem;">
                            Submit
                        </button>
                    </div>
                    <div class="modal-footer" style="justify-content: center; margin-top: 1rem;">
                        <button type="button" class="btn-cancel" onclick="goToStep(2)">Back</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="success-modal" id="successModal">
        <div class="success-content">
            <div class="success-icon">✓</div>
            <h2>Successfully Registered!</h2>
            <p>The patient has been successfully registered and added to the system.</p>
            <button onclick="closeSuccessModal()">Done</button>
        </div>
    </div>

    <div class="modal" id="viewPatientModal">
        <div class="modal-content" style="max-width: 1000px;">
            <div class="modal-header">
                <h2 style="color: #2b8f90; font-size: 1.5rem;">📋 Patient Details</h2>
                <button class="modal-close" type="button" onclick="closeViewPatientModal()">×</button>
            </div>

            <div class="view-modal-scroll">
                <!-- Personal Information Section -->
                <div class="detail-section">
                    <div class="section-header">
                        <span class="section-icon">👤</span>
                        <span class="section-title">Personal Information</span>
                    </div>
                    <div class="detail-card">
                        <div class="detail-field"><span class="field-label">Full Name</span><span class="field-value" id="viewFullName">-</span></div>
                        <div class="detail-field"><span class="field-label">Card No.</span><span class="field-value highlight" id="viewCardNo">-</span></div>
                        <div class="detail-field"><span class="field-label">Case No.</span><span class="field-value highlight" id="viewCaseNo">-</span></div>
                        <div class="detail-field"><span class="field-label">Age</span><span class="field-value" id="viewAge">-</span></div>
                        <div class="detail-field"><span class="field-label">Gender</span><span class="field-value" id="viewGender">-</span></div>
                        <div class="detail-field"><span class="field-label">Weight</span><span class="field-value" id="viewWeight">-</span></div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="detail-section">
                    <div class="section-header">
                        <span class="section-icon">📞</span>
                        <span class="section-title">Contact & Address</span>
                    </div>
                    <div class="detail-card">
                        <div class="detail-field"><span class="field-label">Contact</span><span class="field-value" id="viewContact">-</span></div>
                        <div class="detail-field" style="grid-column: 1 / -1;"><span class="field-label">Email</span><span class="field-value" id="viewEmail">-</span></div>
                        <div class="detail-field" style="grid-column: 1 / -1;"><span class="field-label">Address</span><span class="field-value" id="viewAddress">-</span></div>
                    </div>
                </div>

                <!-- Exposure History Section -->
                <div class="detail-section">
                    <div class="section-header">
                        <span class="section-icon">⚠️</span>
                        <span class="section-title">Exposure History</span>
                    </div>
                    <div class="detail-card">
                        <div class="detail-field"><span class="field-label">CAT (Category)</span><span class="field-value highlight" id="viewCatCategory">-</span></div>
                        <div class="detail-field"><span class="field-label">Bite Type</span><span class="field-value" id="viewBiteType">-</span></div>
                        <div class="detail-field"><span class="field-label">Place of Bite</span><span class="field-value" id="viewPlaceOfBite">-</span></div>
                        <div class="detail-field"><span class="field-label">Source</span><span class="field-value highlight" id="viewSource">-</span></div>
                        <div class="detail-field"><span class="field-label">Severity</span><span class="field-value" id="viewSeverity">-</span></div>
                        <div class="detail-field" style="grid-column: 1 / -1;"><span class="field-label">Treatment Required</span><span class="field-value" id="viewTreatment">-</span></div>
                    </div>
                </div>

                <!-- Vaccine Information Section -->
                <div class="detail-section">
                    <div class="section-header">
                        <span class="section-icon">💉</span>
                        <span class="section-title">Anti-Rabies Vaccine</span>
                    </div>
                    <div class="detail-card">
                        <div class="detail-field"><span class="field-label">Generic Name</span><span class="field-value" id="viewGenericName">-</span></div>
                        <div class="detail-field"><span class="field-label">Brand Name</span><span class="field-value highlight" id="viewBrandName">-</span></div>
                        <div class="detail-field"><span class="field-label">Route</span><span class="field-value" id="viewRoute">-</span></div>
                        <div class="detail-field"><span class="field-label">Dosage</span><span class="field-value highlight" id="viewDosage">-</span></div>
                        <div class="detail-field"><span class="field-label">Dose Schedule</span><span class="field-value highlight" id="viewAntiRabiesDose">-</span></div>
                        <div class="detail-field"><span class="field-label">Date Administered</span><span class="field-value" id="viewAntiRabiesDate">-</span></div>
                    </div>
                </div>

                <!-- Tetanus & RIG Section -->
                <div class="detail-section">
                    <div class="section-header">
                        <span class="section-icon">🛡️</span>
                        <span class="section-title">Tetanus Toxoid & Immunoglobulin</span>
                    </div>
                    <div class="detail-card">
                        <div class="detail-field"><span class="field-label">Tetanus Status</span><span class="field-value highlight" id="viewTetanusStatus">-</span></div>
                        <div class="detail-field"><span class="field-label">Tetanus Dose</span><span class="field-value" id="viewTetanusDose">-</span></div>
                        <div class="detail-field"><span class="field-label">Tetanus Date</span><span class="field-value" id="viewTetanusDate">-</span></div>
                        <div class="detail-field"><span class="field-label">Rabies Immunoglobulin</span><span class="field-value highlight" id="viewRig">-</span></div>
                    </div>
                </div>

                <!-- Metadata Section -->
                <div class="detail-section" style="margin-bottom: 0;">
                    <div style="padding: 1rem; background: #e8f7ee; border-radius: 8px; text-align: center; color: #1f6b38;">
                        <small><strong>Registered:</strong> <span id="viewCreatedAt">-</span></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="editPatientModal">
        <div class="modal-content" style="max-width: 1100px;">
            <div class="modal-header">
                <h2 style="color: #2b8f90; font-size: 1.5rem;">✏️ Edit Patient</h2>
                <button class="modal-close" type="button" onclick="closeEditPatientModal()">×</button>
            </div>
            <p style="color: #666; margin-bottom: 1.5rem; font-size: 0.95rem;">Update the patient's information and save the changes.</p>

            <div class="view-modal-scroll">
                <form id="editPatientForm" method="POST" action="">
                    @csrf
                    @method('PATCH')

                    <div class="detail-section">
                        <div class="section-header">
                            <span class="section-icon">👤</span>
                            <span class="section-title">Personal Information</span>
                        </div>
                        <div class="detail-card">
                            <div class="form-group"><label>Full Name *</label><input type="text" name="full_name" placeholder="Enter full name"></div>
                            <div class="form-group"><label>Card No. *</label><input type="text" name="card_no" placeholder="Card number"></div>
                            <div class="form-group"><label>Case No. *</label><input type="text" name="case_no" placeholder="Case number"></div>
                            <div class="form-group"><label>Contact *</label><input type="tel" name="contact" placeholder="Phone number"></div>
                            <div class="form-group"><label>Age *</label><input type="number" name="age" placeholder="Age"></div>
                            <div class="form-group"><label>Birthday</label><input type="date" name="birthday"></div>
                            <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="Email address"></div>
                            <div class="form-group"><label>Gender *</label><select name="gender"><option value="">Select Gender</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
                            <div class="form-group"><label>Address *</label><input type="text" name="address" placeholder="Street address"></div>
                            <div class="form-group"><label>Weight (kg)</label><input type="number" step="0.1" name="weight" placeholder="Weight (kg)"></div>
                            <div class="form-group"><label>Blood Pressure</label><input type="text" name="blood_pressure" placeholder="e.g. 120/80"></div>
                            <div class="form-group"><label>Temperature</label><input type="text" name="temperature" placeholder="e.g. 36.5°C"></div>
                            <div class="form-group"><label>CAT (Category of exposure) *</label><select name="cat_category"><option value="">Select Category</option><option value="category_i">Category I</option><option value="category_ii">Category II</option><option value="category_iii">Category III</option></select></div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">
                            <span class="section-icon">⚠️</span>
                            <span class="section-title">Exposure History</span>
                        </div>
                        <div class="detail-card">

                            {{-- Treatment Required --}}
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label style="font-weight: 600; margin-bottom: 0.5rem; display: block;">Treatment Required</label>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem 1.25rem;">
                                    @foreach([['prprep','PrPEP'],['pep','PEP'],['booster','Booster'],['tet','TET'],['erig','ERIG'],['hrig','HRIG']] as [$val,$lbl])
                                    <label style="display: inline-flex; align-items: center; gap: 0.4rem; font-weight: normal; cursor: pointer; background: #f0f8f8; border: 1px solid #c8e6e6; border-radius: 6px; padding: 0.3rem 0.75rem; font-size: 0.88rem;">
                                        <input type="checkbox" name="treatment[]" value="{{ $val }}" style="accent-color: #2b8f90;"> {{ $lbl }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Bite info row --}}
                            <div class="form-row" style="margin-top: 0.25rem;">
                                <div class="form-group">
                                    <label>Bite Type</label>
                                    <select name="bite_type">
                                        <option value="">Select bite type</option>
                                        <option value="scratch">Scratch</option>
                                        <option value="bite">Bite</option>
                                        <option value="lick_broken_skin">Lick on broken skin</option>
                                        <option value="open_wound_exposure">Open wounds exposure</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Place of Bite *</label>
                                    <select name="place_of_bite">
                                        <option value="">Select place of bite</option>
                                        <option value="hand">Hand</option>
                                        <option value="arm">Arm</option>
                                        <option value="leg">Leg</option>
                                        <option value="foot">Foot</option>
                                        <option value="face">Face</option>
                                        <option value="neck">Neck</option>
                                        <option value="finger">Finger</option>
                                        <option value="multiple_sites">Multiple sites</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Source *</label>
                                    <select name="source">
                                        <option value="">Select source</option>
                                        <option value="dog">Dog</option>
                                        <option value="cat">Cat</option>
                                        <option value="bat">Bat</option>
                                        <option value="rat">Rat</option>
                                        <option value="monkey">Monkey</option>
                                        <option value="other_animal">Other animal</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Severity</label>
                                    <select name="severity">
                                        <option value="">Select severity</option>
                                        <option value="mild">Mild</option>
                                        <option value="moderate">Moderate</option>
                                        <option value="severe">Severe</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Animal bite details sub-group --}}
                            <div style="grid-column: 1 / -1; border-top: 1px solid #e0eeee; margin: 0.5rem 0 0.75rem; padding-top: 0.75rem;">
                                <p style="font-size: 0.8rem; font-weight: 600; color: #2b8f90; text-transform: uppercase; letter-spacing: 0.04em; margin: 0 0 0.75rem;">Animal Details</p>
                                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;">
                                    <div class="form-group" style="margin: 0;">
                                        <label>Animal Type</label>
                                        <select name="animal_type">
                                            <option value="">Select animal</option>
                                            <option value="dog">Dog</option>
                                            <option value="cat">Cat</option>
                                            <option value="bat">Bat</option>
                                            <option value="rat">Rat</option>
                                            <option value="monkey">Monkey</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin: 0;">
                                        <label>Pet or Stray</label>
                                        <select name="pet_or_stray">
                                            <option value="">Select</option>
                                            <option value="pet">Pet</option>
                                            <option value="stray">Stray</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin: 0;">
                                        <label>Vaccinated Animal</label>
                                        <select name="vaccinated_animal">
                                            <option value="">Select</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                            <option value="unknown">Unknown</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin: 0;">
                                        <label>Animal Status</label>
                                        <input type="text" name="animal_status" placeholder="e.g. Alive, Dead, Stray">
                                    </div>
                                    <div class="form-group" style="margin: 0;">
                                        <label>Date of Bite</label>
                                        <input type="date" name="date_of_bite">
                                    </div>
                                    <div class="form-group" style="margin: 0;">
                                        <label>Washing of Wound</label>
                                        <select name="washing_of_wound">
                                            <option value="">Select</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin: 0;">
                                        <label>Tandok / Tambal</label>
                                        <select name="tandok_tambal">
                                            <option value="">Select</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="margin: 0;">
                                        <label>Owner Name</label>
                                        <input type="text" name="owner_name" placeholder="Animal owner name">
                                    </div>
                                    <div class="form-group" style="margin: 0;">
                                        <label>Owner Address</label>
                                        <input type="text" name="owner_address" placeholder="Owner address">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Medical History --}}
                    <div class="detail-section">
                        <div class="section-header">
                            <span class="section-icon">🏥</span>
                            <span class="section-title">Medical History</span>
                        </div>
                        <div class="detail-card">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label style="font-weight: 600; margin-bottom: 0.5rem; display:block;">Existing Conditions</label>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.6rem;">
                                    <label style="display:flex;align-items:center;gap:0.5rem;font-weight:normal;"><input type="checkbox" name="has_diabetes" value="1"> Diabetes (IDDM)</label>
                                    <label style="display:flex;align-items:center;gap:0.5rem;font-weight:normal;"><input type="checkbox" name="has_cancer" value="1"> Cancer</label>
                                    <label style="display:flex;align-items:center;gap:0.5rem;font-weight:normal;"><input type="checkbox" name="has_organ_transplant" value="1"> Organ Transplant</label>
                                    <label style="display:flex;align-items:center;gap:0.5rem;font-weight:normal;"><input type="checkbox" name="has_ckd" value="1"> CKD</label>
                                    <label style="display:flex;align-items:center;gap:0.5rem;font-weight:normal;"><input type="checkbox" name="has_hiv" value="1"> HIV</label>
                                    <label style="display:flex;align-items:center;gap:0.5rem;font-weight:normal;"><input type="checkbox" name="taking_steroid" value="1"> Taking Steroid</label>
                                    <label style="display:flex;align-items:center;gap:0.5rem;font-weight:normal;"><input type="checkbox" name="has_riv" value="1"> RIV</label>
                                </div>
                            </div>
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label>Allergy</label>
                                <input type="text" name="allergy" placeholder="Any known allergies">
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">
                            <span class="section-icon">💉</span>
                            <span class="section-title">Anti-Rabies Vaccine</span>
                        </div>
                        <div class="detail-card">
                            <div class="form-row" style="width: 100%;">
                                <div class="form-group">
                                    <label>Generic Name *</label>
                                    <select name="generic_name">
                                        <option value="">Select generic name</option>
                                        <option value="purified_vero_cell">Purified vero cell rabies vaccine</option>
                                        <option value="purified_chick_embryo">Purified chick embryo cell vaccine</option>
                                        <option value="human_diploid">Human diploid cell vaccine</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Route *</label>
                                    <select name="route">
                                        <option value="">Select route</option>
                                        <option value="intramuscular">Intramuscular</option>
                                        <option value="intradermal">Intradermal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row" style="width: 100%;">
                                <div class="form-group">
                                    <label>Brand Name *</label>
                                    <select name="brand_name">
                                        <option value="">Select brand</option>
                                        <option value="verorab">Verorab</option>
                                        <option value="speeda">Speeda</option>
                                        <option value="rabiqur">Rabiqur</option>
                                        <option value="abhayrab">Abhayrab</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Dosage *</label>
                                    <select name="dosage">
                                        <option value="">Select dosage</option>
                                        <option value="0_1ml">0.1 ml</option>
                                        <option value="0_5ml">0.5 ml</option>
                                        <option value="1_0ml">1.0 ml</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" style="width: 100%;">
                                <label>Dose *</label>
                                <select name="anti_rabies_dose">
                                    <option value="">Select dose</option>
                                    <option value="day_0">Day 0</option>
                                    <option value="day_3">Day 3</option>
                                    <option value="day_7">Day 7</option>
                                    <option value="day_14">Day 14</option>
                                    <option value="day_28">Day 28</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">
                            <span class="section-icon">🛡️</span>
                            <span class="section-title">Tetanus Toxoid & Immunoglobulin</span>
                        </div>
                        <div class="detail-card">
                            <div class="form-group">
                                <label>Tetanus Status *</label>
                                <select name="tetanus_status">
                                    <option value="">Select tetanus toxoid status</option>
                                    <option value="valid">Valid</option>
                                    <option value="expired">Expired</option>
                                    <option value="unknown">Unknown</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Dose *</label>
                                <select name="tetanus_dose">
                                    <option value="">Select dose</option>
                                    <option value="dose1">Dose 1</option>
                                    <option value="dose2">Dose 2</option>
                                    <option value="dose3">Dose 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Rabies Immunoglobulin *</label>
                                <select name="rabies_immunoglobulin">
                                    <option value="">Select immunoglobulin type</option>
                                    <option value="erig">ERIG</option>
                                    <option value="hrig">HRIG</option>
                                    <option value="none">None</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer" style="justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
                        <button type="button" class="btn-cancel" onclick="closeEditPatientModal()">Cancel</button>
                        <button type="submit" class="btn-submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

    <script>
        const adminCsrf = '{{ csrf_token() }}';

        async function togglePatientStatus(id, status, btn) {
            const ok = await showConfirm('Are you sure you want to change the status?', 'Change status');
            if (!ok) return;

            btn.disabled = true;
            try {
                const res = await fetch("{{ url('/admin/patients') }}/" + id + '/status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': adminCsrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status })
                });

                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data.message || 'Failed to update status');

                const row = btn.closest('tr');
                if (row) {
                    row.dataset.status = status;
                    const badge = row.querySelector('[class^="badge-"]');
                    if (badge) {
                        badge.className = 'badge-' + status;
                        badge.textContent = status === 'approved' ? 'Approved' : 'Pending';
                    }
                    btn.remove();
                }

                const cell = document.getElementById('patient-status-' + id);
                if (cell) {
                    const badgeClass = status === 'approved' ? 'badge-approved' : 'badge-not_approved';
                    const badgeText = status === 'approved' ? 'Approved' : 'Pending';
                    cell.innerHTML = '<span class="' + badgeClass + '">' + badgeText + '</span>';
                }

                showToast(data.message || 'Status updated', 'success');
            } catch (err) {
                showToast(err.message || 'Failed to update', 'error');
            }
        }
    </script>
    </div>

    <script>
        let currentStep = 1;

        function openAddPatientModal() {
            currentStep = 1;
            showStep(1);
            document.getElementById('addPatientModal').classList.add('active');
        }

        function closeAddPatientModal() {
            document.getElementById('addPatientModal').classList.remove('active');
            currentStep = 1;
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('active');
            closeAddPatientModal();
        }

        function openViewPatientModalFromButton(button) {
            const patient = button.dataset;

            document.getElementById('viewFullName').textContent = patient.fullName || '-';
            document.getElementById('viewCardNo').textContent = patient.cardNo || '-';
            document.getElementById('viewCaseNo').textContent = patient.caseNo || '-';
            document.getElementById('viewContact').textContent = patient.contact || '-';
            document.getElementById('viewAge').textContent = patient.age || '-';
            document.getElementById('viewEmail').textContent = patient.email || '-';
            document.getElementById('viewGender').textContent = patient.gender ? patient.gender.charAt(0).toUpperCase() + patient.gender.slice(1) : '-';
            document.getElementById('viewAddress').textContent = patient.address || '-';
            document.getElementById('viewWeight').textContent = patient.weight ? Math.round(parseFloat(patient.weight)) + ' kg' : '-';
            document.getElementById('viewCatCategory').textContent = patient.catCategory ? patient.catCategory.replaceAll('_', ' ').toUpperCase() : '-';
            document.getElementById('viewTreatment').textContent = patient.treatmentRequired ? patient.treatmentRequired.split(',').map(item => item.trim().toUpperCase()).join(', ') : '-';
            document.getElementById('viewBiteType').textContent = patient.biteType ? patient.biteType.replaceAll('_', ' ') : '-';
            document.getElementById('viewPlaceOfBite').textContent = patient.placeOfBite ? patient.placeOfBite.replaceAll('_', ' ') : '-';
            document.getElementById('viewSource').textContent = (patient.animalType || patient.source) ? (patient.animalType || patient.source).replaceAll('_', ' ') : '-';
            document.getElementById('viewSeverity').textContent = patient.severity ? patient.severity.charAt(0).toUpperCase() + patient.severity.slice(1) : '-';
            document.getElementById('viewGenericName').textContent = patient.genericName ? patient.genericName.replaceAll('_', ' ') : '-';
            document.getElementById('viewRoute').textContent = patient.route ? patient.route.charAt(0).toUpperCase() + patient.route.slice(1) : '-';
            document.getElementById('viewBrandName').textContent = patient.brandName ? patient.brandName.charAt(0).toUpperCase() + patient.brandName.slice(1) : '-';
            document.getElementById('viewDosage').textContent = patient.dosage ? patient.dosage.replace('_', '.').replace('ml', ' ml') : '-';
            document.getElementById('viewAntiRabiesDose').textContent = patient.antiRabiesDose ? patient.antiRabiesDose.replaceAll('_', ' ') : '-';
            document.getElementById('viewAntiRabiesDate').textContent = patient.antiRabiesDate || '-';
            document.getElementById('viewTetanusStatus').textContent = patient.tetanusStatus ? patient.tetanusStatus.charAt(0).toUpperCase() + patient.tetanusStatus.slice(1) : '-';
            document.getElementById('viewTetanusDose').textContent = patient.tetanusDose ? patient.tetanusDose.replaceAll('_', ' ') : '-';
            document.getElementById('viewTetanusDate').textContent = patient.tetanusDate || '-';
            document.getElementById('viewRig').textContent = patient.rabiesImmunoglobulin ? patient.rabiesImmunoglobulin.toUpperCase() : '-';
            document.getElementById('viewCreatedAt').textContent = patient.createdAt || '-';
            document.getElementById('viewPatientModal').classList.add('active');
        }

        function openEditPatientModalFromButton(button) {
            const patient = button.parentElement.querySelector('.btn-view').dataset;
            const form = document.getElementById('editPatientForm');

            form.action = button.dataset.updateUrl;

            // Personal information
            form.querySelector('[name="full_name"]').value = patient.fullName || '';
            form.querySelector('[name="card_no"]').value = patient.cardNo || '';
            form.querySelector('[name="case_no"]').value = patient.caseNo || '';
            form.querySelector('[name="contact"]').value = patient.contact || '';
            form.querySelector('[name="age"]').value = patient.age || '';
            form.querySelector('[name="birthday"]').value = patient.birthday || '';
            form.querySelector('[name="email"]').value = patient.email || '';
            form.querySelector('[name="gender"]').value = patient.gender || '';
            form.querySelector('[name="address"]').value = patient.address || '';
            form.querySelector('[name="weight"]').value = patient.weight || '';
            form.querySelector('[name="blood_pressure"]').value = patient.bloodPressure || '';
            form.querySelector('[name="temperature"]').value = patient.temperature || '';
            form.querySelector('[name="cat_category"]').value = patient.catCategory || '';

            // Exposure history
            form.querySelector('[name="bite_type"]').value = patient.biteType || '';
            form.querySelector('[name="place_of_bite"]').value = patient.placeOfBite || '';
            form.querySelector('[name="source"]').value = patient.animalType || patient.source || '';
            form.querySelector('[name="severity"]').value = patient.severity || '';

            // Animal bite details
            form.querySelector('[name="animal_type"]').value = patient.animalType || '';
            form.querySelector('[name="pet_or_stray"]').value = patient.petOrStray || '';
            form.querySelector('[name="vaccinated_animal"]').value = patient.vaccinatedAnimal || '';
            form.querySelector('[name="animal_status"]').value = patient.animalStatus || '';
            form.querySelector('[name="date_of_bite"]').value = patient.dateOfBite || '';
            form.querySelector('[name="washing_of_wound"]').value = patient.washingOfWound || '';
            form.querySelector('[name="tandok_tambal"]').value = patient.tandokTambal || '';
            form.querySelector('[name="owner_name"]').value = patient.ownerName || '';
            form.querySelector('[name="owner_address"]').value = patient.ownerAddress || '';

            // Medical history — boolean checkboxes
            ['has_diabetes','has_cancer','has_organ_transplant','has_ckd','has_hiv','taking_steroid','has_riv'].forEach(function(field) {
                const key = field.replace(/_([a-z])/g, (_, c) => c.toUpperCase()); // camelCase
                const cb = form.querySelector('[name="' + field + '"]');
                if (cb) cb.checked = patient[key] === '1' || patient[key] === 'true' || patient[key] === true;
            });
            form.querySelector('[name="allergy"]').value = patient.allergy || '';

            // Anti-rabies vaccine
            form.querySelector('[name="generic_name"]').value = patient.genericName || '';
            form.querySelector('[name="route"]').value = patient.route || '';
            form.querySelector('[name="brand_name"]').value = patient.brandName || '';
            form.querySelector('[name="dosage"]').value = patient.dosage || '';
            form.querySelector('[name="anti_rabies_dose"]').value = patient.antiRabiesDose || '';

            // Tetanus & immunoglobulin
            form.querySelector('[name="tetanus_status"]').value = patient.tetanusStatus || '';
            form.querySelector('[name="tetanus_dose"]').value = patient.tetanusDose || '';
            form.querySelector('[name="rabies_immunoglobulin"]').value = patient.rabiesImmunoglobulin || '';

            // Treatment checkboxes
            const treatmentValues = patient.treatmentRequired ? patient.treatmentRequired.split(',').map(item => item.trim()) : [];
            form.querySelectorAll('input[name="treatment[]"]').forEach((checkbox) => {
                checkbox.checked = treatmentValues.includes(checkbox.value);
            });

            document.getElementById('editPatientModal').classList.add('active');
        }

        function closeEditPatientModal() {
            document.getElementById('editPatientModal').classList.remove('active');
        }

        function closeViewPatientModal() {
            document.getElementById('viewPatientModal').classList.remove('active');
        }

        function goToStep(step) {
            currentStep = step;
            showStep(step);
        }

        function setStepRequirements(activeStep) {
            const stepRequirements = {
                1: ['full_name', 'card_no', 'case_no', 'contact', 'age', 'gender', 'address', 'weight', 'cat_category'],
                2: ['place_of_bite', 'source', 'generic_name', 'route', 'brand_name', 'dosage', 'anti_rabies_dose', 'anti_rabies_date'],
                3: ['tetanus_status', 'tetanus_dose', 'tetanus_date', 'rabies_immunoglobulin'],
            };

            const form = document.getElementById('addPatientForm');
            const fields = form.querySelectorAll('input, select, textarea');

            fields.forEach((field) => {
                field.required = false;
            });

            (stepRequirements[activeStep] || []).forEach((name) => {
                const field = form.querySelector(`[name="${name}"]`);
                if (field) {
                    field.required = true;
                }
            });
        }

        function showStep(step) {
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step3').style.display = 'none';

            document.getElementById('step' + step).style.display = 'block';
            setStepRequirements(step);
            document.querySelector('.modal-content').scrollTop = 0;
        }

        document.getElementById('addPatientModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeAddPatientModal();
            }
        });

        document.getElementById('successModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeSuccessModal();
            }
        });

        document.getElementById('viewPatientModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeViewPatientModal();
            }
        });

        document.getElementById('editPatientModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeEditPatientModal();
            }
        });

        function filterByStatus() {
            // Delegate to unified filter that handles both search and status
            filterPatients();
        }

        @if (session('success'))
            document.getElementById('successModal').classList.add('active');
        @endif
    </script>
@endsection
