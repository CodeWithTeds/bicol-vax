<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BicolVax Admin') - BicolVax Clinic Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        /* Header Navigation */
        header {
            background: linear-gradient(135deg, #2b8f90 0%, #1f6566 100%);
            color: white;
            padding: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .brand img {
            width: 60px;
            height: 60px;
            background: transparent;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
            list-style: none;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            font-size: 0.95rem;
        }

        .nav-menu a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .nav-menu a.active {
            background-color: #50c878;
            font-weight: 600;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .user-profile:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #50c878;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            margin-top: 1rem;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: block;
            width: 100%;
            padding: 0.75rem 1.5rem;
            border: none;
            background: none;
            text-align: left;
            color: #333;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 0.95rem;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #f0f0f0;
        }

        .dropdown-menu a:first-child {
            border-radius: 8px 8px 0 0;
        }

        .dropdown-menu a:last-child,
        .dropdown-menu button:last-child {
            border-radius: 0 0 8px 8px;
            border-top: 1px solid #e0e0e0;
            color: #d9534f;
        }

        /* Main Content */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #2b8f90;
        }

        .page-header p {
            color: #666;
            font-size: 0.95rem;
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .stat-card-title {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2b8f90;
            margin-bottom: 0.5rem;
        }

        .stat-card-change {
            font-size: 0.85rem;
            color: #999;
        }

        .content-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .content-header h2 {
            color: #2b8f90;
            font-size: 1.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #50c878;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3fa368;
        }

        .btn-secondary {
            background-color: #2b8f90;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #1f6566;
        }

        .btn-danger {
            background-color: #d9534f;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c9302c;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f9f9f9;
        }

        th {
            padding: 1rem;
            text-align: left;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                padding: 1rem;
            }

            .nav-menu {
                gap: 1rem;
                font-size: 0.9rem;
            }

            .nav-menu a {
                padding: 0.4rem 0.8rem;
            }

            .main-container {
                padding: 1rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .dropdown-menu {
                right: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <header>
        <div class="header-container">
            <a href="{{ route('admin.dashboard') }}" class="brand">
                <img src="{{ asset('logo.png') }}" alt="BicolVax Logo" style="width: 45px; height: 45px; object-fit: contain;">
                <div>
                    <div style="font-size: 1.1rem;">BicolVax</div>
                    <div style="font-size: 0.75rem; opacity: 0.9;">Clinic Management</div>
                </div>
            </a>

            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('admin.dashboard') }}" class="@if(Route::currentRouteName() == 'admin.dashboard') active @endif">Dashboard</a></li>
                    <li><a href="{{ route('admin.patients') }}" class="@if(Route::currentRouteName() == 'admin.patients') active @endif">Patients</a></li>
                    <li><a href="{{ route('admin.appointments') }}" class="@if(Route::currentRouteName() == 'admin.appointments') active @endif">Appointments</a></li>
                    <li><a href="{{ route('admin.reminders') }}" class="@if(Route::currentRouteName() == 'admin.reminders') active @endif">Reminders</a></li>
                    <li><a href="{{ route('admin.notifications') }}" class="@if(Route::currentRouteName() == 'admin.notifications') active @endif" style="position:relative;">
                        🔔 Notifications
                        @php $navUnreadCount = \App\Models\AdminNotification::unread()->count(); @endphp
                        @if($navUnreadCount > 0)
                            <span style="position:absolute;top:-4px;right:-8px;background:#ef4444;color:white;font-size:0.65rem;font-weight:700;padding:2px 5px;border-radius:10px;min-width:16px;text-align:center;">{{ $navUnreadCount }}</span>
                        @endif
                    </a></li>
                    <li><a href="{{ route('admin.reports') }}" class="@if(Route::currentRouteName() == 'admin.reports') active @endif">Reports</a></li>
                    <li><a href="{{ route('admin.settings') }}" class="@if(Route::currentRouteName() == 'admin.settings') active @endif">Settings</a></li>
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" onclick="toggleDropdown()">
                    <div class="user-avatar">A</div>
                    <div>Admin</div>
                </div>
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="#profile">My Profile</a>
                    <a href="#settings">Account Settings</a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        @yield('content')
    </main>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            const userProfile = document.querySelector('.user-profile');
            
            if (!userProfile.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>
    <!-- Global Confirm Modal and Toast -->
    <style>
        .gv-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 4000;
            padding: 1rem;
        }

        .gv-modal-overlay.active { display: flex; }

        .gv-modal {
            background: white;
            width: 100%;
            max-width: 560px;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(2,6,23,0.4);
            padding: 1.25rem 1.5rem;
            text-align: left;
        }

        .gv-modal h3 { margin: 0 0 0.5rem 0; color: #1f2937; }
        .gv-modal p { margin: 0 0 1rem 0; color: #475569; }

        .gv-modal-actions { display:flex; gap:0.5rem; justify-content:flex-end; margin-top:1rem; }
        .gv-btn { padding:0.6rem 1rem; border-radius:8px; border:none; cursor:pointer; font-weight:700; }
        .gv-btn.confirm { background: linear-gradient(135deg,#ef4444 0%,#ef4444 100%); color:white; }
        .gv-btn.cancel { background:#eef2ff; color:#3730a3; }

        .gv-toast { position: fixed; top: 1rem; right: 1rem; z-index: 4500; min-width: 220px; max-width: 420px; display:none; border-radius: 10px; padding: 0.75rem 1rem; box-shadow: 0 8px 30px rgba(2,6,23,0.2); color: white; font-weight:700; }
        .gv-toast.show { display:block; }
        .gv-toast.success { background: linear-gradient(135deg,#10b981 0%,#059669 100%); }
        .gv-toast.error { background: linear-gradient(135deg,#ef4444 0%,#dc2626 100%); }
        .gv-toast.info { background: linear-gradient(135deg,#3b82f6 0%,#2563eb 100%); }
    </style>

    <div class="gv-modal-overlay" id="gvConfirmOverlay" aria-hidden="true">
        <div class="gv-modal" role="dialog" aria-modal="true" id="gvConfirmModal">
            <h3 id="gvConfirmTitle">Confirm action</h3>
            <p id="gvConfirmMessage">Are you sure?</p>
            <div class="gv-modal-actions">
                <button class="gv-btn cancel" id="gvConfirmCancel">Cancel</button>
                <button class="gv-btn confirm" id="gvConfirmOk">Yes, proceed</button>
            </div>
        </div>
    </div>

    <div id="gvToast" class="gv-toast info" role="status" aria-live="polite"></div>

    <script>
        // Promise-based confirmation modal
        function showConfirm(message, title = 'Confirm action') {
            return new Promise((resolve) => {
                const overlay = document.getElementById('gvConfirmOverlay');
                const msg = document.getElementById('gvConfirmMessage');
                const ttl = document.getElementById('gvConfirmTitle');
                const ok = document.getElementById('gvConfirmOk');
                const cancel = document.getElementById('gvConfirmCancel');

                ttl.textContent = title;
                msg.textContent = message;

                function cleanup(result) {
                    ok.removeEventListener('click', onOk);
                    cancel.removeEventListener('click', onCancel);
                    overlay.classList.remove('active');
                    resolve(result);
                }

                function onOk(e) { e.preventDefault(); cleanup(true); }
                function onCancel(e) { e.preventDefault(); cleanup(false); }

                ok.addEventListener('click', onOk);
                cancel.addEventListener('click', onCancel);

                overlay.classList.add('active');
            });
        }

        function showToast(message, type = 'info', ms = 2500) {
            const t = document.getElementById('gvToast');
            t.className = 'gv-toast ' + type + ' show';
            t.textContent = message;
            setTimeout(() => { t.classList.remove('show'); }, ms);
        }

        // Intercept forms with data-confirm attribute
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.dataset.confirm === 'true') {
                e.preventDefault();
                const msg = form.dataset.confirmMessage || 'Are you sure?';
                showConfirm(msg).then(ok => { if (ok) form.submit(); });
            }
        }, true);
    </script>
</body>
</html>
