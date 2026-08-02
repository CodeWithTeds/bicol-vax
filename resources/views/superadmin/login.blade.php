<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login – BicolVax</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: linear-gradient(135deg, #2b8f90 0%, #1f6566 100%);
            padding: 0.75rem 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
            max-width: 1400px;
            margin: 0 auto;
        }

        .brand img { width: 42px; height: 42px; object-fit: contain; }

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
            margin-left: 0.35rem;
            vertical-align: middle;
        }

        .page-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .login-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
        }

        .login-card h1 {
            font-size: 1.8rem;
            color: #2b8f90;
            margin-bottom: 0.4rem;
        }

        .login-card .subtitle {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        .form-group { margin-bottom: 1rem; }

        .form-group label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        .form-group input:focus { outline: none; border-color: #2b8f90; }

        .btn-login {
            width: 100%;
            padding: 0.8rem;
            background-color: #50c878;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background-color 0.3s;
        }

        .btn-login:hover { background-color: #3fa368; }

        .error-msg {
            background: #fde8e8;
            color: #7f1d1d;
            border: 1px solid #fca5a5;
            padding: 0.7rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            color: #999;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .back-link:hover { color: #2b8f90; }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <img src="{{ asset('logo.png') }}" alt="BicolVax Logo">
            <div>
                <div style="font-size:1.1rem; font-weight:600;">BicolVax <span class="sa-badge">Super Admin</span></div>
                <div style="font-size:0.72rem; opacity:0.85;">Multi-Branch Management</div>
            </div>
        </div>
    </header>

    <div class="page-body">
        <div class="login-card">
            <h1>Super Admin Login</h1>
            <p class="subtitle">Sign in to manage all BicolVax branches.</p>

            @if($errors->any())
                <div class="error-msg">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('superadmin.login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           placeholder="superadmin@bicolvax.com" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position:relative;">
                        <input type="password" id="password" name="password"
                               placeholder="••••••••" required style="padding-right:44px;">
                        <button type="button" id="togglePwd" aria-label="Show password"
                                style="position:absolute;right:8px;top:50%;transform:translateY(-50%);border:none;background:transparent;cursor:pointer;color:#666;"></button>
                    </div>
                </div>
                <button type="submit" class="btn-login">Sign In as Super Admin</button>
            </form>

            <a href="{{ route('admin.login') }}" class="back-link">← Branch Admin Login</a>
        </div>
    </div>

    <script>
        (function(){
            const pwd = document.getElementById('password');
            const btn = document.getElementById('togglePwd');
            const eyeOn  = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>';
            const eyeOff = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.67 21.67 0 0 1 5.06-5.94"/><path d="M1 1l22 22"/><path d="M9.88 9.88A3 3 0 0 0 14.12 14.12"/></svg>';
            btn.innerHTML = eyeOn;
            btn.addEventListener('click', function(){
                const shown = pwd.type === 'text';
                pwd.type = shown ? 'password' : 'text';
                btn.innerHTML = shown ? eyeOn : eyeOff;
            });
        })();
    </script>
</body>
</html>
