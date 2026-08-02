<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard') - BicolVax Clinic</title>
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
    </style>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const profile = document.querySelector('.user-profile');
            if (!profile.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>
</head>
<body>
    <!-- Header Navigation -->
    <header>
        <div class="header-container">
            <a href="{{ route('user.dashboard') }}" class="brand">
                <img src="{{ asset('logo.png') }}" alt="BicolVax Logo" style="width: 60px; height: 60px; object-fit: contain; background: transparent;">
                <div>
                    <div style="font-size: 1.1rem;">BicolVax</div>
                    <div style="font-size: 0.75rem; opacity: 0.9;">Clinic Management</div>
                </div>
            </a>

            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('user.dashboard') }}" class="@if(Route::currentRouteName() == 'user.dashboard') active @endif">Dashboard</a></li>
                    <li><a href="{{ route('user.booking') }}" class="@if(Route::currentRouteName() == 'user.booking') active @endif">Booking Appointment</a></li>
                    <li><a href="{{ route('user.my-appointments') }}" class="@if(Route::currentRouteName() == 'user.my-appointments') active @endif">My Schedule</a></li>
                    <li><a href="{{ route('user.reminders') }}" class="@if(Route::currentRouteName() == 'user.reminders') active @endif">Reminders</a></li>
                    <li><a href="{{ route('user.profile') }}" class="@if(Route::currentRouteName() == 'user.profile') active @endif">Profile</a></li>
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-profile" onclick="toggleDropdown()">
                    <div class="user-avatar">U</div>
                    <span>User</span>
                </div>
                <div class="dropdown-menu" id="userDropdown">
                    <a href="#">Profile Settings</a>
                    <button onclick="document.getElementById('logoutForm').submit();" style="cursor: pointer;">Log out</button>
                </div>
            </div>
        </div>
    </header>

    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Main Content -->
    <div class="main-container">
        @yield('content')
    </div>
</body>
</html>
