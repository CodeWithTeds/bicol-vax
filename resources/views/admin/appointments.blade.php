@extends('layouts.admin')

@section('title', 'Appointments')

@section('content')
    <style>
        .appointments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .appointments-header h1 {
            font-size: 1.8rem;
            color: #333;
            font-weight: 700;
        }

        .status-badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background-color: #ffeb3b;
            color: #333;
        }

        .status-not-approved {
            background-color: #f7c6c7;
            color: #8a1f2d;
        }

        .status-approved {
            background-color: #4caf50;
            color: white;
        }

        .status-rejected {
            background-color: #f44336;
            color: white;
        }

        .action-icons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .icon-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-approve {
            background-color: #4caf50;
            color: white;
        }

        .btn-approve:hover {
            background-color: #3fa368;
        }

        .btn-reject {
            background-color: #f44336;
            color: white;
        }

        .btn-reject:hover {
            background-color: #d9302c;
        }

        .btn-edit {
            background-color: #ff9800;
            color: white;
        }

        .btn-edit:hover {
            background-color: #e68900;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        .tab-filters {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin: 1rem 0 1.5rem;
        }

        .tab-btn {
            border: 1px solid #d9dee8;
            background: #fff;
            color: #445066;
            padding: 0.65rem 1rem;
            border-radius: 999px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .tab-btn.active {
            background: #2b8f90;
            color: #fff;
            border-color: #2b8f90;
            box-shadow: 0 8px 16px rgba(43, 143, 144, 0.18);
        }

        .tab-btn:hover {
            transform: translateY(-1px);
        }

        .appointment-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .summary-card {
            background: linear-gradient(135deg, #ffffff 0%, #f7fbfc 100%);
            border: 1px solid #e5eef0;
            border-radius: 12px;
            padding: 1rem;
        }

        .summary-label {
            font-size: 0.85rem;
            color: #6a778c;
            margin-bottom: 0.35rem;
        }

        .summary-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1d2a3a;
        }

        .patient-summary {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 180px;
        }

        .patient-avatar {
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
            border-radius: 50%;
            object-fit: cover;
            background: #d9eeee;
            border: 3px solid #fff;
            box-shadow: 0 2px 8px rgba(32, 89, 90, 0.18);
        }

        .patient-avatar-fallback {
            display: grid;
            place-items: center;
            color: #226d6e;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .patient-name {
            color: #1e3131;
            font-weight: 750;
        }

        .view-profile-btn {
            margin-top: 0.2rem;
            padding: 0;
            border: 0;
            background: transparent;
            color: #197779;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
        }

        .view-profile-btn:hover {
            color: #115758;
            text-decoration: underline;
        }

        .profile-card {
            padding: 1.75rem;
            text-align: center;
        }

        .profile-card .patient-avatar {
            width: 112px;
            height: 112px;
            margin-bottom: 0.9rem;
            border-width: 4px;
        }

        .profile-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
            margin-top: 1.25rem;
            text-align: left;
        }

        .profile-details div {
            padding: 0.75rem;
            border-radius: 8px;
            background: #f4fbfb;
        }

        .profile-details span {
            display: block;
            margin-bottom: 0.2rem;
            color: #668181;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>

    <div class="appointments-header">
        <h1>Appointment Management</h1>
    </div>

    <div class="appointment-summary">
        <div class="summary-card">
            <div class="summary-label">All Appointments</div>
            <div class="summary-value" id="allCount">{{ $totalAppointments ?? 0 }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Approved</div>
            <div class="summary-value" id="approvedCount">{{ $approvedAppointments ?? 0 }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Not Approved</div>
            <div class="summary-value" id="notApprovedCount">{{ $notApprovedAppointments ?? 0 }}</div>
        </div>
    </div>

    <div class="tab-filters">
        <button type="button" class="tab-btn active" data-filter="all" onclick="setAppointmentFilter('all')">All Appointments</button>
        <button type="button" class="tab-btn" data-filter="approved" onclick="setAppointmentFilter('approved')">Approved Appointments</button>
        <button type="button" class="tab-btn" data-filter="not-approved" onclick="setAppointmentFilter('not-approved')">Not Approved</button>
    </div>

    <!-- Appointment List Card -->
    <div class="content-card">
        <div style="padding: 1.5rem; border-bottom: 2px solid #ddd;">
            <h2 style="font-size: 1.1rem; color: #333; margin: 0;" id="appointmentsSectionTitle">All Appointments</h2>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Email</th>
                    <th>Birthday</th>
                    <th>Age</th>
                    <th>Appointment Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="appointmentsTableBody">
                <tr><td colspan="7" style="text-align: center; color: #999; padding: 2rem;">Loading appointments...</td></tr>
            </tbody>
        </table>
    </div>

    <div class="modal-overlay" id="viewProfileModal" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="viewProfileTitle" tabindex="-1" style="max-width: 500px;">
            <button class="close-icon" onclick="closeProfileModal()" aria-label="Close profile">×</button>
            <div class="modal-header">
                <h2 id="viewProfileTitle">Patient Profile</h2>
                <p>Booking information and profile photo</p>
            </div>
            <div class="profile-card" id="profileCardContent"></div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="confirmAptModal" aria-hidden="true">
        <div class="confirmation-modal-content" role="dialog" aria-modal="true" aria-labelledby="confirmAptTitle" tabindex="-1">
            <div class="confirmation-header">
                <h2 id="confirmAptTitle">Confirm Action</h2>
            </div>

            <div class="confirmation-body">
                <div id="confirmAptMessage">Are you sure?</div>
                <div id="confirmAptDetails" style="margin-top: 1rem; padding: 1rem; background: rgba(43,143,144,0.05); border-left: 4px solid #42d4de; border-radius: 8px; font-size: 0.95rem; color: #666;"></div>
            </div>

            <div class="confirmation-buttons">
                <button type="button" class="modal-button close" onclick="closeConfirmAptModal()">Cancel</button>
                <button type="button" class="modal-button submit" onclick="proceedWithAction()">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteAptModal" aria-hidden="true">
        <div class="confirmation-modal-content" role="dialog" aria-modal="true" aria-labelledby="deleteAptTitle" tabindex="-1">
            <div class="confirmation-header" style="background: linear-gradient(135deg, #f44336 0%, #e91e63 100%);">
                <h2 id="deleteAptTitle">Delete Appointment</h2>
            </div>

            <div class="confirmation-body">
                <div id="deleteAptMessage" style="color: #d32f2f; font-weight: 600;">
                    ⚠️ Are you sure you want to delete this appointment?
                </div>
                <div id="deleteAptDetails" style="margin-top: 1rem; padding: 1rem; background: #ffebee; border-left: 4px solid #f44336; border-radius: 8px; font-size: 0.95rem; color: #666;"></div>
                <div style="margin-top: 0.75rem; padding: 1rem; background: rgba(244,67,54,0.05); border-radius: 8px; font-size: 0.88rem; color: #d32f2f; font-weight: 500;">
                    ⚠️ This action cannot be undone!
                </div>
            </div>

            <div class="confirmation-buttons">
                <button type="button" class="modal-button close" onclick="closeDeleteAptModal()">Cancel</button>
                <button type="button" class="modal-button submit" onclick="proceedWithDelete()" style="background: linear-gradient(135deg, #f44336 0%, #e91e63 100%); box-shadow: 0 12px 28px rgba(244,67,54,0.22);">Delete</button>
            </div>
        </div>
    </div>

    <!-- Edit Appointment Modal -->
    <div class="modal-overlay" id="editAptModal" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="editAptTitle" tabindex="-1">
            <button class="close-icon" onclick="closeEditAptModal()" aria-label="Close modal">×</button>

            <div class="modal-header">
                <h2 id="editAptTitle">Edit Appointment</h2>
                <p>Review and update appointment details</p>
            </div>

            <form onsubmit="saveAppointmentChanges(event)">
                <div id="editAptForm" style="padding: 1.5rem 2rem;">
                    <input type="hidden" id="editAptId">

                    <div class="form-group">
                        <label for="editAptPatient">👤 Patient Name</label>
                        <input type="text" id="editAptPatient" required>
                    </div>

                    <div class="form-group">
                        <label for="editAptContact">📱 Contact Number</label>
                        <input type="tel" id="editAptContact">
                    </div>

                    <div class="form-group row">
                        <div class="form-group">
                            <label for="editAptBirthday">📅 Birthday</label>
                            <input type="date" id="editAptBirthday">
                        </div>
                        <div class="form-group">
                            <label for="editAptAge">🎂 Age</label>
                            <input type="number" id="editAptAge">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="editAptEmail">📧 Email</label>
                        <input type="email" id="editAptEmail" disabled>
                    </div>

                    <div class="form-group">
                        <label for="editAptAddress">📍 Address</label>
                        <input type="text" id="editAptAddress">
                    </div>

                    <div class="form-group">
                        <label for="editAptGender">👥 Gender</label>
                        <input type="text" id="editAptGender" disabled>
                    </div>

                    <div class="form-group">
                        <label for="editAptAppointmentDate">📅 Appointment Date</label>
                        <input type="date" id="editAptAppointmentDate">
                    </div>

                    <div class="form-group">
                        <label for="editAptStatus">✅ Status</label>
                        <select id="editAptStatus" required>
                            <option value="not_approved">Not Approved</option>
                            <option value="approved">Approved</option>
                        </select>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="modal-button close" onclick="closeEditAptModal()">
                        <span>Cancel</span>
                    </button>
                    <button type="submit" class="modal-button submit">
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedule Time Modal (shown when approving) -->
    <div class="modal-overlay" id="scheduleTimeModal" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="scheduleTimeTitle" tabindex="-1">
            <button class="close-icon" onclick="closeScheduleTimeModal()" aria-label="Close modal">×</button>
            <div class="modal-header">
                <h2 id="scheduleTimeTitle">Set Appointment Time</h2>
                <p>Choose date and time to schedule for the patient.</p>
            </div>

            <div style="padding: 1.25rem 2rem;">
                <input type="hidden" id="scheduleAptId">

                <div class="form-group">
                    <label for="scheduleAptDate">Appointment Date</label>
                    <input type="date" id="scheduleAptDate">
                </div>

                <div class="form-group">
                    <label for="scheduleAptTime">Appointment Time</label>
                    <input type="time" id="scheduleAptTime">
                </div>
            </div>

            <div class="modal-buttons">
                <button type="button" class="modal-button close" onclick="closeScheduleTimeModal()">Cancel</button>
                <button type="button" class="modal-button submit" onclick="submitScheduleTime()">Save & Approve</button>
            </div>
        </div>
    </div>

    <style>
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 0.75rem;
            background: rgba(8, 24, 27, 0.54);
            backdrop-filter: blur(4px);
            overflow-y: auto;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            position: relative;
            width: min(600px, calc(100% - 1.5rem));
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.55);
            padding: 0;
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            overflow-y: auto;
            scrollbar-width: none;
        }

        .modal-content::-webkit-scrollbar {
            display: none;
        }

        .modal-header {
            flex-shrink: 0;
            background: linear-gradient(135deg, #2b8f90 0%, #42d4de 100%);
            padding: 2rem 3.5rem 2rem 2rem;
            border-radius: 24px 24px 0 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .modal-header h2 {
            color: white;
            margin: 0 0 0.5rem 0;
            font-size: 1.6rem;
            font-weight: 700;
        }

        .modal-header p {
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
            font-size: 0.95rem;
        }

        .close-icon {
            position: absolute;
            top: 1.2rem;
            right: 1.2rem;
            width: 2.2rem;
            height: 2.2rem;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            cursor: pointer;
            font-size: 1.4rem;
            color: white;
            display: grid;
            place-items: center;
            transition: all 200ms ease;
            z-index: 10;
        }

        .close-icon:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: rotate(90deg) scale(1.05);
        }

        #editAptForm {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: none;
        }

        #editAptForm::-webkit-scrollbar {
            display: none;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.6rem;
            font-weight: 600;
            font-size: 0.96rem;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.95rem 1.1rem;
            border-radius: 14px;
            border: 2px solid rgba(43, 143, 144, 0.1);
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.98), rgba(66, 212, 222, 0.04));
            font: inherit;
            color: #333;
            transition: all 280ms cubic-bezier(0.2, 0.9, 0.2, 1);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #42d4de;
            box-shadow: 0 0 0 6px rgba(43, 143, 144, 0.15), 0 8px 20px rgba(43, 143, 144, 0.12);
            background-color: #fff;
            transform: translateY(-1px);
        }

        .form-group.row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .modal-buttons {
            flex-shrink: 0;
            display: flex;
            gap: 0.85rem;
            padding: 1.5rem 2rem;
            border-top: 1px solid rgba(43, 143, 144, 0.1);
            margin-top: 1rem;
        }

        .modal-button {
            flex: 1;
            min-height: 48px;
            border: none;
            border-radius: 12px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: all 240ms cubic-bezier(0.2, 0.9, 0.2, 1);
            font-size: 0.95rem;
        }

        .modal-button.submit {
            color: white;
            background: linear-gradient(135deg, #2b8f90 0%, #42d4de 100%);
            box-shadow: 0 12px 28px rgba(43, 143, 144, 0.22);
        }

        .modal-button.submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(43, 143, 144, 0.28);
        }

        .modal-button.submit:active {
            transform: translateY(0);
        }

        .modal-button.close {
            background: rgba(43, 143, 144, 0.08);
            color: #333;
            border: 1.5px solid rgba(43, 143, 144, 0.2);
        }

        .modal-button.close:hover {
            background: rgba(43, 143, 144, 0.12);
        }

        @media (max-width: 640px) {
            .modal-content {
                width: calc(100% - 1.5rem);
            }
            .modal-header {
                padding: 1.25rem 3rem 1.25rem 1.5rem;
            }
            .modal-header h2 {
                font-size: 1.4rem;
            }
            #editAptForm {
                padding: 1.25rem 1.5rem !important;
            }
            .modal-buttons {
                padding: 1rem 1.5rem;
            }
            .form-group.row {
                grid-template-columns: 1fr;
            }
        }

        /* Confirmation Modal Styles */
        .confirmation-modal-content {
            position: relative;
            width: min(450px, calc(100% - 1.5rem));
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.55);
            overflow: hidden;
            animation: slideInUp 300ms cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .confirmation-header {
            background: linear-gradient(135deg, #2b8f90 0%, #42d4de 100%);
            padding: 1.5rem 1.5rem;
            text-align: center;
        }

        .confirmation-header h2 {
            color: white;
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .confirmation-body {
            padding: 1.5rem;
        }

        #confirmAptMessage {
            font-size: 1rem;
            color: #333;
            font-weight: 500;
            text-align: center;
            line-height: 1.6;
        }

        .confirmation-buttons {
            display: flex;
            gap: 0.85rem;
            padding: 1rem 1.5rem 1.5rem;
            border-top: 1px solid rgba(43, 143, 144, 0.1);
        }

        @media (max-width: 640px) {
            .confirmation-modal-content {
                width: calc(100% - 1.5rem);
            }
            .confirmation-header h2 {
                font-size: 1.2rem;
            }
            .confirmation-body {
                padding: 1rem 1.25rem;
            }
            .confirmation-buttons {
                padding: 0.85rem 1.25rem 1.25rem;
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        const csrfToken = @json(csrf_token());
        const appointments = @json($appointmentsPayload ?? []);
        let currentAppointmentFilter = 'all';
        let pendingAction = null;
        let pendingDelete = null;
        let isLoading = false;

        function normalizeAppointmentStatus(status) {
            return (status || 'Not Approved').toLowerCase();
        }

        function getFilteredAppointments() {
            if (currentAppointmentFilter === 'approved') {
                return appointments.filter(apt => normalizeAppointmentStatus(apt.status) === 'approved');
            }

            if (currentAppointmentFilter === 'not-approved') {
                return appointments.filter(apt => normalizeAppointmentStatus(apt.status) !== 'approved');
            }

            return appointments;
        }

        function updateAppointmentCounts() {
            document.getElementById('allCount').textContent = appointments.length;
            document.getElementById('approvedCount').textContent = appointments.filter(apt => normalizeAppointmentStatus(apt.status) === 'approved').length;
            document.getElementById('notApprovedCount').textContent = appointments.filter(apt => normalizeAppointmentStatus(apt.status) !== 'approved').length;
        }

        function setAppointmentFilter(filter) {
            currentAppointmentFilter = filter;

            document.querySelectorAll('.tab-btn').forEach(button => {
                button.classList.toggle('active', button.dataset.filter === filter);
            });

            const titleMap = {
                all: 'All Appointments',
                approved: 'Approved Appointments',
                'not-approved': 'Not Approved Appointments',
            };

            document.getElementById('appointmentsSectionTitle').textContent = titleMap[filter] || 'All Appointments';
            renderAppointments();
        }

        function escapeHtml(value) {
            const element = document.createElement('div');
            element.textContent = value || '';
            return element.innerHTML;
        }

        function initials(name) {
            return (name || 'Patient')
                .trim()
                .split(/\s+/)
                .slice(0, 2)
                .map(part => part.charAt(0).toUpperCase())
                .join('');
        }

        function profileAvatar(apt, size) {
            if (apt.profile_photo_url) {
                return `<img class="patient-avatar" src="${apt.profile_photo_url}" alt="${escapeHtml(apt.patient)} profile photo" style="width: ${size}px; height: ${size}px; flex-basis: ${size}px;" onerror="this.replaceWith(Object.assign(document.createElement('span'), {className: 'patient-avatar patient-avatar-fallback', textContent: '${initials(apt.patient)}'}))">`;
            }

            return `<span class="patient-avatar patient-avatar-fallback" style="width: ${size}px; height: ${size}px; flex-basis: ${size}px;">${initials(apt.patient)}</span>`;
        }

        function viewAppointmentProfile(id) {
            const apt = appointments.find(appointment => appointment.id === id);
            if (!apt) return;

            document.getElementById('profileCardContent').innerHTML = `
                ${profileAvatar(apt, 112)}
                <h3 style="margin: 0; color: #1e3131;">${escapeHtml(apt.patient)}</h3>
                <p style="margin: 0.3rem 0 0; color: #668181;">${escapeHtml(apt.email || 'No email provided')}</p>
                <div class="profile-details">
                    <div><span>Contact</span>${escapeHtml(apt.contact || 'Not provided')}</div>
                    <div><span>Birthday</span>${escapeHtml(apt.birthday || 'Not provided')}</div>
                    <div><span>Age</span>${escapeHtml(apt.age || 'Not provided')}</div>
                    <div><span>Appointment</span>${escapeHtml(apt.appointment_date || 'Not scheduled')}</div>
                </div>`;

            const modal = document.getElementById('viewProfileModal');
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeProfileModal() {
            const modal = document.getElementById('viewProfileModal');
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = 'auto';
        }

        window.profileAvatar = profileAvatar;
        window.viewAppointmentProfile = viewAppointmentProfile;
        window.closeProfileModal = closeProfileModal;

        function renderAppointments() {
            const tbody = document.getElementById('appointmentsTableBody');
            const filteredAppointments = getFilteredAppointments();
            
            updateAppointmentCounts();

            if (filteredAppointments.length === 0) {
                const emptyMessage = currentAppointmentFilter === 'all'
                    ? 'No appointments found'
                    : currentAppointmentFilter === 'approved'
                        ? 'No approved appointments found'
                        : 'No not approved appointments found';
                tbody.innerHTML = `<tr><td colspan="7" style="text-align: center; color: #999; padding: 2rem;">${emptyMessage}</td></tr>`;
                return;
            }

            tbody.innerHTML = filteredAppointments.map(apt => {
                const status = normalizeAppointmentStatus(apt.status);
                const statusLabel = status === 'approved' ? 'Approved' : 'Not Approved';
                const statusClass = status === 'approved' ? 'status-approved' : 'status-not-approved';

                return `
                <tr>
                    <td>
                        <div class="patient-summary">
                            ${window.profileAvatar(apt, 52)}
                            <div>
                                <div class="patient-name">${escapeHtml(apt.patient)}</div>
                                <button type="button" class="view-profile-btn" onclick="window.viewAppointmentProfile(${apt.id})">View profile</button>
                            </div>
                        </div>
                    </td>
                    <td>${apt.email || '-'}</td>
                    <td>${apt.birthday || '-'}</td>
                    <td>${apt.age || '-'}</td>
                    <td>${apt.appointment_date || '-'}</td>
                    <td>
                        <span class="status-badge ${statusClass}">
                            ${statusLabel}
                        </span>
                    </td>
                    <td>
                        <div class="action-icons">
                            <button class="icon-btn btn-approve" title="Approve" onclick='confirmAppointmentAction(${apt.id}, "approved", "${apt.patient}")'>✓</button>
                            <button class="icon-btn btn-reject" title="Not Approved" onclick='confirmAppointmentAction(${apt.id}, "not_approved", "${apt.patient}")'>✕</button>
                            <button class="icon-btn btn-edit" title="Edit" onclick="editAppointment(${apt.id})">✎</button>
                            <button class="icon-btn btn-delete" title="Delete" onclick='confirmDeleteAppointment(${apt.id}, "${apt.patient}")'>🗑</button>
                        </div>
                    </td>
                </tr>
            `;
            }).join('');
        }

        function updateAppointmentStatus(id, status) {
            const apt = appointments.find(a => a.id === id);
            if (!apt) {
                resetLoadingState();
                return;
            }

            // Update immediately in local array
            apt.status = status;
            renderAppointments();

            // Also send to server
            fetch(apt.statusUpdateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ status }),
            })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        console.error('Server update failed:', data.message || 'Unable to update appointment status.');
                    }
                    resetLoadingState();
                })
                .catch((error) => {
                    console.error('Update failed:', error);
                    resetLoadingState();
                });
        }

        function resetLoadingState() {
            isLoading = false;
        }

        function confirmAppointmentAction(id, status, patientName) {
            pendingAction = { id, status, patientName };
            // If approving, open schedule modal to set time; otherwise use confirm modal
            if (status === 'approved') {
                openScheduleModal(id, patientName);
                return;
            }

            const statusLabel = status === 'approved' ? 'Approve' : 'Not Approve';
            const statusIcon = status === 'approved' ? '✓' : '✕';

            document.getElementById('confirmAptMessage').innerHTML = `
                <div style="margin-bottom: 0.5rem;">
                    ${statusIcon} <strong>${statusLabel} Appointment</strong>
                </div>
                <div style="font-size: 0.9rem; color: #666;">for <strong>${patientName}</strong>?</div>
            `;

            document.getElementById('confirmAptDetails').innerHTML = `
                <strong>Action:</strong> ${statusLabel}<br>
                <strong>Patient:</strong> ${patientName}<br>
                <strong>New Status:</strong> ${status === 'approved' ? 'Approved' : 'Not Approved'}
            `;

            openConfirmAptModal();
        }

        function openScheduleModal(id, patientName) {
            const apt = appointments.find(a => a.id === id);
            if (!apt) return;
            document.getElementById('scheduleAptId').value = id;
            document.getElementById('scheduleAptDate').value = apt.appointment_date_raw || '';
            // If appointment_time exists populate; else default to 09:00
            const timeVal = apt.appointment_time || '09:00';
            document.getElementById('scheduleAptTime').value = timeVal;
            openScheduleTimeModal();
        }

        function openScheduleTimeModal() {
            const modal = document.getElementById('scheduleTimeModal');
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeScheduleTimeModal() {
            const modal = document.getElementById('scheduleTimeModal');
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = 'auto';
            // clear pendingAction if any
            pendingAction = null;
        }

        async function submitScheduleTime() {
            if (isLoading) return;
            const id = Number(document.getElementById('scheduleAptId').value);
            const date = document.getElementById('scheduleAptDate').value;
            const time = document.getElementById('scheduleAptTime').value;
            const apt = appointments.find(a => a.id === id);
            if (!apt) return;

            // Close modal
            closeScheduleTimeModal();

            // Update locally
            apt.status = 'approved';
            apt.appointment_date = date || apt.appointment_date;
            apt.appointment_time = time || apt.appointment_time || '09:00';
            renderAppointments();

            // Send to server
            isLoading = true;
            try {
                const res = await fetch(apt.statusUpdateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ status: 'approved', appointment_date: date, appointment_time: time }),
                });

                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    console.error('Scheduling failed:', data.message || 'Unable to schedule appointment.');
                }
            } catch (e) {
                console.error('Scheduling error:', e);
            } finally {
                isLoading = false;
            }
        }

        function openConfirmAptModal() {
            const modal = document.getElementById('confirmAptModal');
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeConfirmAptModal() {
            const modal = document.getElementById('confirmAptModal');
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = 'auto';
            pendingAction = null;
        }

        function proceedWithAction() {
            if (!pendingAction || isLoading) return;
            
            const { id, status } = pendingAction;
            
            // Close modal immediately
            closeConfirmAptModal();
            
            // Update status in background
            isLoading = true;
            updateAppointmentStatus(id, status);
        }

        function confirmDeleteAppointment(id, patientName) {
            pendingDelete = { id, patientName };
            
            document.getElementById('deleteAptMessage').innerHTML = `
                <div style="margin-bottom: 0.5rem;">
                    🗑️ <strong>Delete Appointment</strong>
                </div>
                <div style="font-size: 0.9rem; color: #d32f2f;">for <strong>${patientName}</strong>?</div>
            `;
            
            document.getElementById('deleteAptDetails').innerHTML = `
                <strong>Patient:</strong> ${patientName}<br>
                <strong>Action:</strong> Permanent deletion<br>
                <strong>Warning:</strong> This will permanently remove the appointment record
            `;
            
            openDeleteAptModal();
        }

        function openDeleteAptModal() {
            const modal = document.getElementById('deleteAptModal');
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteAptModal() {
            const modal = document.getElementById('deleteAptModal');
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = 'auto';
            pendingDelete = null;
        }

        function proceedWithDelete() {
            if (!pendingDelete || isLoading) return;
            
            const { id } = pendingDelete;
            const apt = appointments.find(a => a.id === id);
            
            if (!apt || !apt.deleteUrl) {
                console.error('Unable to delete appointment.');
                return;
            }

            // Close modal immediately
            closeDeleteAptModal();
            
            // Remove immediately from local array
            const index = appointments.findIndex(a => a.id === id);
            if (index > -1) {
                appointments.splice(index, 1);
            }
            
            renderAppointments();
            
            // Delete in background
            isLoading = true;

            fetch(apt.deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
                .then(async (response) => {
                    if (!response.ok) {
                        const data = await response.json().catch(() => ({}));
                        console.error('Server delete failed:', data.message || 'Delete failed.');
                    }
                    isLoading = false;
                })
                .catch((error) => {
                    console.error('Delete failed:', error);
                    isLoading = false;
                });
        }

        function resetDeleteLoadingState() {
            isLoading = false;
        }

        function editAppointment(id) {
            const apt = appointments.find(a => a.id === id);
            if (!apt) return;

            // Populate modal with appointment data
            document.getElementById('editAptId').value = apt.id;
            document.getElementById('editAptPatient').value = apt.patient;
            document.getElementById('editAptEmail').value = apt.email || '';
            document.getElementById('editAptContact').value = apt.contact || '';
            document.getElementById('editAptBirthday').value = apt.birthday_raw || '';
            document.getElementById('editAptAge').value = apt.age || '';
            document.getElementById('editAptGender').value = apt.gender || '';
            document.getElementById('editAptAddress').value = apt.address || '';
            document.getElementById('editAptAppointmentDate').value = apt.appointment_date_raw || '';
            
            // Set status - ensure it matches dropdown options
            const statusValue = apt.status && apt.status.toLowerCase() === 'approved' ? 'approved' : 'not_approved';
            document.getElementById('editAptStatus').value = statusValue;

            // Open modal
            openEditAptModal();
        }

        function openEditAptModal() {
            const modal = document.getElementById('editAptModal');
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeEditAptModal() {
            const modal = document.getElementById('editAptModal');
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = 'auto';
        }

        function saveAppointmentChanges(event) {
            event.preventDefault();
            if (isLoading) return;
            
            const aptId = document.getElementById('editAptId').value;
            const patient = document.getElementById('editAptPatient').value;
            const status = document.getElementById('editAptStatus').value;
            const contact = document.getElementById('editAptContact').value;
            const birthday = document.getElementById('editAptBirthday').value;
            const age = document.getElementById('editAptAge').value;
            const address = document.getElementById('editAptAddress').value;
            const appointment_date = document.getElementById('editAptAppointmentDate').value;
            const apt = appointments.find(a => a.id == aptId);

            if (!apt) return;

            // Close modal immediately
            closeEditAptModal();
            
            // Update immediately in local array
            apt.patient = patient;
            apt.status = status;
            apt.contact = contact;
            apt.birthday = birthday;
            apt.age = age;
            apt.address = address;
            apt.appointment_date = appointment_date;
            renderAppointments();
            
            // Update in background
            isLoading = true;

            fetch(apt.statusUpdateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ patient, status, contact, birthday, age, address, appointment_date }),
            })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        console.error('Server update failed:', data.message || 'Unable to update appointment.');
                    }
                    isLoading = false;
                })
                .catch((error) => {
                    console.error('Update failed:', error);
                    isLoading = false;
                });
        }

        function resetEditLoadingState() {
            isLoading = false;
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeProfileModal();
                closeEditAptModal();
                closeConfirmAptModal();
                closeDeleteAptModal();
            }
        });

        // Close modal when clicking outside
        document.getElementById('editAptModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeEditAptModal();
            }
        });

        document.getElementById('confirmAptModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeConfirmAptModal();
            }
        });

        document.getElementById('deleteAptModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteAptModal();
            }
        });

        document.getElementById('viewProfileModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeProfileModal();
            }
        });

        // Render appointments on page load
        document.addEventListener('DOMContentLoaded', () => {
            renderAppointments();

            // Poll server for updates every 3 seconds and refresh appointments immediately when changed
            let lastKnown = 0;
            // initialize lastKnown from server-provided appointments if available (milliseconds)
            try {
                const parsed = appointments.map(a => a.registered ? Date.parse(a.registered) : 0);
                lastKnown = parsed.length ? Math.max(...parsed) : 0;
            } catch (e) {
                lastKnown = 0;
            }

            setInterval(async () => {
                try {
                    const res = await fetch("{{ route('admin.appointments.json') }}", { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (!data) return;

                    const serverLast = data.last_updated || null;
                    const serverLastMs = serverLast ? serverLast * 1000 : null;
                    if (serverLastMs && serverLastMs !== lastKnown) {
                        // update local list
                        appointments.length = 0;
                        (data.appointments || []).forEach(a => appointments.push(a));
                        lastKnown = serverLast;
                        renderAppointments();
                    }
                } catch (e) {
                    console.error('Failed to poll appointments:', e);
                }
            }, 3000);
        });
    </script>
@endsection
