<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BicolVax Super Admin') - BicolVax Clinic Management</title>
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
            width: 45px;
            height: 45px;
            object-fit: contain;
            background: transparent;
        }

        .sa-badge {
            display: inline-block;
            background: rgba(255,255,255,0.25);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-left: 0.4rem;
            vertical-align: middle;
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

        .dropdown-menu a:first-child { border-radius: 8px 8px 0 0; }
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

        /* Cards */
        .content-card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Stats */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #2b8f90;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .stat-card.accent-green  { border-left-color: #50c878; }
        .stat-card.accent-orange { border-left-color: #ff9800; }
        .stat-card.accent-blue   { border-left-color: #3b82f6; }
        .stat-card.accent-red    { border-left-color: #ef4444; }
        .stat-card.accent-purple { border-left-color: #8b5cf6; }

        .stat-card .stat-label {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.4rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .stat-card .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2b8f90;
        }

        .stat-card.accent-green .stat-number  { color: #50c878; }
        .stat-card.accent-orange .stat-number { color: #ff9800; }
        .stat-card.accent-blue .stat-number   { color: #3b82f6; }
        .stat-card.accent-red .stat-number    { color: #ef4444; }
        .stat-card.accent-purple .stat-number { color: #8b5cf6; }

        /* Buttons */
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

        .btn-primary   { background-color: #50c878; color: white; }
        .btn-primary:hover { background-color: #3fa368; }
        .btn-secondary { background-color: #2b8f90; color: white; }
        .btn-secondary:hover { background-color: #1f6566; }
        .btn-warning   { background-color: #ff9800; color: white; }
        .btn-warning:hover { background-color: #e68900; }
        .btn-danger    { background-color: #d9534f; color: white; }
        .btn-danger:hover { background-color: #c9302c; }
        .btn-outline   { background: white; color: #2b8f90; border: 1px solid #2b8f90; }
        .btn-outline:hover { background: #f0fafa; }

        /* Badges */
        .badge { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600; }
        .badge-active    { background: #d4edda; color: #155724; }
        .badge-inactive  { background: #f8d7da; color: #721c24; }
        .badge-approved  { background: #d4edda; color: #155724; }
        .badge-pending   { background: #fff3cd; color: #856404; }

        /* Table */
        table { width: 100%; border-collapse: collapse; background: #fff; }
        thead th { background: #f6fbfb; color: #234; font-weight: 700; padding: 0.85rem; border-bottom: 1px solid #e6eef0; }
        tbody td { padding: 0.85rem; border-bottom: 1px solid #f1f5f6; }
        tbody tr:nth-child(even) { background: #fbfdfd; }
        tbody tr:hover { background: #f0fafa; }

        /* Modals */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3000;
            padding: 1rem;
        }

        .modal-overlay.active { display: flex; }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 560px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-content h2 { color: #2b8f90; font-size: 1.4rem; margin-bottom: 1.5rem; }

        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.4rem; font-weight: 600; color: #333; font-size: 0.9rem; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%; padding: 0.75rem;
            border: 1px solid #ddd; border-radius: 4px;
            font-size: 0.95rem; font-family: inherit;
        }
        .form-group input:focus,
        .form-group select:focus { outline: none; border-color: #2b8f90; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        .modal-footer { display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem; }
        .modal-footer button { padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.95rem; font-weight: 600; }
        .btn-cancel { background: #e0e0e0; color: #333; }
        .btn-submit { background: #50c878; color: white; }

        /* Alert */
        .alert { padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-success { background: #e8f7ee; color: #1f6b38; border: 1px solid #bfe8cb; }
        .alert-error   { background: #fde8e8; color: #7f1d1d; border: 1px solid #fca5a5; }

        /* Responsive */
        @media (max-width: 768px) {
            .header-container { padding: 1rem; }
            .nav-menu { gap: 1rem; }
            .nav-menu a { padding: 0.4rem 0.8rem; font-size: 0.85rem; }
            .main-container { padding: 1rem; }
            .dashboard-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .dropdown-menu { right: 1rem; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <a href="{{ route('superadmin.dashboard') }}" class="brand">
                <img src="{{ asset('logo.png') }}" alt="BicolVax Logo">
                <div>
                    <div style="font-size: 1.1rem;">BicolVax <span class="sa-badge">Super Admin</span></div>
                    <div style="font-size: 0.75rem; opacity: 0.9;">Multi-Branch Management</div>
                </div>
            </a>

            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('superadmin.dashboard') }}" class="{{ Route::currentRouteName() === 'superadmin.dashboard' ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('superadmin.branches') }}" class="{{ str_starts_with(Route::currentRouteName() ?? '', 'superadmin.branches') ? 'active' : '' }}">Branches</a></li>
                    <li><a href="{{ route('superadmin.admins') }}"   class="{{ str_starts_with(Route::currentRouteName() ?? '', 'superadmin.admins') ? 'active' : '' }}">Admins</a></li>
                    <li><a href="{{ route('superadmin.reports') }}"  class="{{ Route::currentRouteName() === 'superadmin.reports' ? 'active' : '' }}">Reports</a></li>
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" onclick="toggleDropdown()">
                    <div class="user-avatar">S</div>
                    <span>Super Admin</span>
                </div>
                <div class="dropdown-menu" id="dropdownMenu">
                    <form action="{{ route('superadmin.logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="main-container">
        @yield('content')
    </main>

    <script>
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('active');
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('dropdownMenu');
            const profile  = document.querySelector('.user-profile');
            if (!profile.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });

        function openModal(id)  { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.classList.remove('active');
            }
        });
    </script>

    <!-- Global Confirm Modal and Toast (same as admin) -->
    <style>
        .gv-modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); display:none; align-items:center; justify-content:center; z-index:4000; padding:1rem; }
        .gv-modal-overlay.active { display:flex; }
        .gv-modal { background:white; width:100%; max-width:560px; border-radius:12px; box-shadow:0 20px 60px rgba(2,6,23,0.4); padding:1.25rem 1.5rem; }
        .gv-modal h3 { margin:0 0 0.5rem 0; color:#1f2937; }
        .gv-modal p  { margin:0 0 1rem 0; color:#475569; }
        .gv-modal-actions { display:flex; gap:0.5rem; justify-content:flex-end; margin-top:1rem; }
        .gv-btn { padding:0.6rem 1rem; border-radius:8px; border:none; cursor:pointer; font-weight:700; }
        .gv-btn.confirm { background:#ef4444; color:white; }
        .gv-btn.cancel  { background:#eef2ff; color:#3730a3; }
        .gv-toast { position:fixed; top:1rem; right:1rem; z-index:4500; min-width:220px; max-width:420px; display:none; border-radius:10px; padding:0.75rem 1rem; box-shadow:0 8px 30px rgba(2,6,23,0.2); color:white; font-weight:700; }
        .gv-toast.show { display:block; }
        .gv-toast.success { background:linear-gradient(135deg,#10b981,#059669); }
        .gv-toast.error   { background:linear-gradient(135deg,#ef4444,#dc2626); }
        .gv-toast.info    { background:linear-gradient(135deg,#3b82f6,#2563eb); }
    </style>

    <div class="gv-modal-overlay" id="gvConfirmOverlay">
        <div class="gv-modal" role="dialog">
            <h3 id="gvConfirmTitle">Confirm action</h3>
            <p id="gvConfirmMessage">Are you sure?</p>
            <div class="gv-modal-actions">
                <button class="gv-btn cancel"  id="gvConfirmCancel">Cancel</button>
                <button class="gv-btn confirm" id="gvConfirmOk">Yes, proceed</button>
            </div>
        </div>
    </div>

    <div id="gvToast" class="gv-toast info" role="status" aria-live="polite"></div>

    <script>
        function showConfirm(message, title = 'Confirm action') {
            return new Promise((resolve) => {
                const overlay = document.getElementById('gvConfirmOverlay');
                document.getElementById('gvConfirmTitle').textContent   = title;
                document.getElementById('gvConfirmMessage').textContent = message;
                const ok     = document.getElementById('gvConfirmOk');
                const cancel = document.getElementById('gvConfirmCancel');
                function cleanup(r) { ok.removeEventListener('click', onOk); cancel.removeEventListener('click', onCancel); overlay.classList.remove('active'); resolve(r); }
                function onOk(e)     { e.preventDefault(); cleanup(true); }
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
            setTimeout(() => t.classList.remove('show'), ms);
        }

        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.dataset.confirm === 'true') {
                e.preventDefault();
                showConfirm(form.dataset.confirmMessage || 'Are you sure?').then(ok => { if (ok) form.submit(); });
            }
        }, true);
    </script>
</body>
</html>
