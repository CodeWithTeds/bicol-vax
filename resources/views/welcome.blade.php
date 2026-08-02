<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BicolVax | Animal Bite & Vaccination Center</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:600,700|plus-jakarta-sans:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --primary: #2b8f90;
            --primary-light: #42d4de;
            --primary-soft: rgba(43, 143, 144, 0.12);
            --text-dark: #0f2d31;
            --text-muted: #5f7074;
            --border: rgba(15, 45, 49, 0.12);
            --surface: rgba(255, 255, 255, 0.86);
            --shadow: 0 20px 50px rgba(8, 24, 27, 0.12);
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            color: var(--text-dark);
            background:
                radial-gradient(circle at top left, rgba(66, 212, 222, 0.2), transparent 28%),
                radial-gradient(circle at bottom right, rgba(43, 143, 144, 0.18), transparent 26%),
                linear-gradient(180deg, #f7fbfb 0%, #eef7f7 100%);
            overflow-x: hidden;
        }

        a { color: var(--primary); }

        .page {
            position: relative;
            min-height: 100vh;
            padding: 1.25rem;
        }

        .blob {
            position: fixed;
            z-index: 0;
            border-radius: 999px;
            filter: blur(4px);
            opacity: 0.35;
            pointer-events: none;
        }

        .blob-one { width: 18rem; height: 18rem; left: -6rem; top: -5rem; background: rgba(66, 212, 222, 0.35); }
        .blob-two { width: 22rem; height: 22rem; right: -8rem; top: 10rem; background: rgba(43, 143, 144, 0.22); }
        .blob-three { width: 16rem; height: 16rem; right: 12%; bottom: -6rem; background: rgba(67, 98, 222, 0.14); }

        .shell {
            width: min(1180px, calc(100% - 2rem));
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border: 1px solid var(--border);
            border-radius: 22px;
            background: var(--surface);
            backdrop-filter: blur(14px);
            box-shadow: var(--shadow);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .brand img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 16px;
        }

        .brand strong { font-size: 1.15rem; letter-spacing: 0.2px; }
        .brand span { font-size: 0.94rem; color: var(--text-muted); }

        .chip-link {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.75rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            border: 1px solid rgba(43, 143, 144, 0.18);
            background: rgba(43, 143, 144, 0.08);
            transition: transform 180ms ease, background 180ms ease;
        }

        .chip-link:hover { transform: translateY(-1px); background: rgba(43, 143, 144, 0.12); }

        .hero {
            margin-top: 1.25rem;
            padding: 2.5rem 0 2rem;
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 2rem;
            align-items: start;
        }

        .hero-copy {
            padding: 1.5rem 0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .eyebrow {
            display: inline-flex;
            align-self: flex-start;
            padding: 0.5rem 0.85rem;
            border-radius: 999px;
            background: rgba(43, 143, 144, 0.09);
            color: var(--primary);
            font-weight: 700;
            font-size: 0.88rem;
            letter-spacing: 0.2px;
        }

        h1 {
            margin: 0;
            font-family: "Fraunces", serif;
            font-size: clamp(2.6rem, 5vw, 4.8rem);
            line-height: 0.98;
            letter-spacing: -0.02em;
            max-width: 10ch;
        }

        .lede {
            margin: 0;
            max-width: 62ch;
            font-size: 1.05rem;
            line-height: 1.75;
            color: var(--text-muted);
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            margin-top: 0.35rem;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-width: 176px;
            min-height: 48px;
            padding: 0.9rem 1.3rem;
            border-radius: 14px;
            border: 1px solid transparent;
            text-decoration: none;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.98rem;
            transition: transform 180ms ease, box-shadow 180ms ease, background 180ms ease;
        }

        .button.primary {
            color: white;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            box-shadow: 0 14px 26px rgba(43, 143, 144, 0.22);
        }

        .button.secondary {
            color: var(--text-dark);
            background: rgba(255, 255, 255, 0.75);
            border-color: var(--border);
        }

        .button:hover { transform: translateY(-2px); }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .feature-card {
            padding: 1.1rem;
            border-radius: 20px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.8);
            box-shadow: var(--shadow);
        }

        .feature-icon { width: 42px; height: 42px; color: var(--primary); }
        .feature-card h2 {
            margin: 0.9rem 0 0.35rem;
            font-size: 1.05rem;
        }
        .feature-card p {
            margin: 0;
            color: var(--text-muted);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .hero-footer {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 1rem;
        }

        .mini-pill {
            padding: 0.55rem 0.85rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .login-modal-overlay,
        .admin-modal-overlay,
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

        /* Enhanced modal visuals and transitions */
        .modal-overlay {
            transition: opacity 220ms ease, visibility 220ms ease;
            opacity: 0;
            visibility: hidden;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            transform: translateY(6vh) scale(0.98);
            opacity: 0;
            transition: transform 240ms cubic-bezier(.2,.9,.2,1), opacity 200ms ease;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .modal-content:focus {
            outline: 3px solid rgba(43,143,144,0.12);
            outline-offset: 4px;
        }

        @media (max-width: 640px) {
            .modal-content { border-radius: 16px; padding: 1.5rem; }
            .login-modal-content, .admin-modal-content { width: min(100%, calc(100vw - 1.5rem)); }
            .close-icon { top: 0.75rem; right: 0.75rem; width: 2rem; height: 2rem; font-size: 1.1rem; }
            #registerModal .modal-header { padding: 1.25rem 1.5rem !important; margin: -1.5rem -1.5rem 1.5rem -1.5rem !important; }
            #register-form { padding: 1.25rem 0 !important; }
            #registerModal .modal-buttons { padding: 0 !important; margin-top: 1.5rem !important; gap: 0.75rem; }
            .form-group.row { grid-template-columns: 1fr; }
            .modal-button, .login-button, .admin-button { min-height: 44px; font-size: 0.92rem; }
            #registerModal .modal-header h2 { font-size: 1.5rem !important; }
            #registerModal .modal-header p { font-size: 0.88rem !important; }
        }

        .login-modal-overlay.active,
        .admin-modal-overlay.active,
        .modal-overlay.active {
            display: flex;
        }

        .login-modal-content,
        .admin-modal-content,
        .modal-content {
            position: relative;
            width: min(800px, calc(100% - 1.5rem));
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.55);
            padding: 2rem;
        }

        .login-modal-content { width: min(480px, calc(100% - 1.5rem)); }
        .admin-modal-content { width: min(520px, calc(100% - 1.5rem)); }

        /* Enhanced registration modal content */
        #registerModal .modal-content {
            max-height: 90vh;
            overflow-y: scroll;
            overflow-x: hidden;
            scroll-behavior: smooth;
            padding: 0;
            display: flex;
            flex-direction: column;
            scrollbar-width: none;
        }

        /* Hide scrollbar for webkit browsers */
        #registerModal .modal-content::-webkit-scrollbar {
            display: none;
        }

        #registerModal .modal-header {
            flex-shrink: 0;
        }

        #register-form {
            flex: 1;
            padding: 1.5rem 2rem;
            overflow-y: auto;
            scrollbar-width: none;
        }

        #register-form::-webkit-scrollbar {
            display: none;
        }

        #registerModal .modal-buttons {
            flex-shrink: 0;
            padding: 0 2rem 2rem 2rem;
        }

        .close-icon,
        .login-close-icon,
        .admin-close-icon {
            position: absolute;
            top: 1.2rem;
            right: 1.2rem;
            width: 2.2rem;
            height: 2.2rem;
            border: none;
            border-radius: 50%;
            background: rgba(43, 143, 144, 0.12);
            cursor: pointer;
            font-size: 1.2rem;
            display: grid;
            place-items: center;
            transition: all 200ms ease;
            z-index: 10;
        }

        .close-icon:hover,
        .login-close-icon:hover,
        .admin-close-icon:hover {
            background: rgba(43, 143, 144, 0.22);
            transform: rotate(90deg) scale(1.05);
        }

        .close-icon:active,
        .login-close-icon:active,
        .admin-close-icon:active {
            transform: rotate(90deg) scale(0.95);
        }

        .modal-header,
        .login-modal-header,
        .admin-modal-header {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        /* Enhanced registration modal header */
        #registerModal .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%) !important;
            padding: 2rem 3.5rem 2rem 2rem !important;
            margin: -2rem -2rem 2rem -2rem !important;
            border-radius: 24px 24px 0 0 !important;
            text-align: center !important;
            position: relative;
            overflow: hidden;
        }

        #registerModal .modal-header::before {
            content: '💉';
            position: absolute;
            right: -0.5rem;
            top: -0.5rem;
            font-size: 5rem;
            opacity: 0.08;
        }

        #registerModal .modal-header::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1), transparent 50%);
            pointer-events: none;
        }

        #registerModal .modal-header h2 {
            color: white !important;
            margin-bottom: 0.5rem !important;
            font-size: 1.8rem !important;
            font-weight: 700 !important;
            letter-spacing: -0.5px;
            position: relative;
            z-index: 1;
        }

        #registerModal .modal-header p {
            color: rgba(255, 255, 255, 0.85) !important;
            font-size: 0.95rem;
            margin: 0 !important;
            position: relative;
            z-index: 1;
        }

        .modal-header h2,
        .login-modal-header h2,
        .admin-modal-header h2 {
            margin: 0;
            font-family: "Fraunces", serif;
            font-size: 1.9rem;
        }

        .modal-header p,
        .login-modal-header p,
        .admin-modal-header p {
            margin: 0.45rem 0 0;
            color: var(--text-muted);
        }

        /* Enhanced registration modal header */
        #registerModal .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 2rem 1.5rem;
            margin: -2rem -2rem 2rem -2rem;
            border-radius: 24px 24px 0 0;
            text-align: left;
            position: relative;
            overflow: hidden;
        }

        #registerModal .modal-header::before {
            content: '💉';
            position: absolute;
            right: -0.5rem;
            top: -0.5rem;
            font-size: 5rem;
            opacity: 0.08;
        }

        #registerModal .modal-header::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1), transparent 50%);
            pointer-events: none;
        }

        #registerModal .modal-header h2 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            position: relative;
            z-index: 1;
        }

        #registerModal .modal-header p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.95rem;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .form-section { display: block; }
        .form-group,
        .login-form-group,
        .admin-form-group { margin-bottom: 1rem; }

        /* Enhanced form section with better visual grouping */
        #register-form {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        #register-form .form-group.row {
            margin-bottom: 0.8rem;
        }

        #register-form > .form-group {
            margin-bottom: 1.1rem;
        }

        #register-form > .form-group:last-of-type {
            margin-bottom: 0.5rem;
        }

        #register-form .modal-login-link {
            margin-top: 0.75rem;
        }

        .form-help {
            display: block;
            margin-top: 0.4rem;
            margin-left: 0;
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
            opacity: 0.8;
        }

        .form-group label,
        .login-form-group label,
        .admin-form-group label {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.6rem;
            font-weight: 600;
            font-size: 0.96rem;
            color: var(--text-dark);
            letter-spacing: 0.3px;
        }

        /* Add icons to labels via data attributes (for registration form) */
        #register-form [for="email"]::before { content: '📧'; }
        #register-form [for="fullname"]::before { content: '👤'; }
        #register-form [for="birthday"]::before { content: '📅'; }
        #register-form [for="age"]::before { content: '🎂'; }
        #register-form [for="gender"]::before { content: '👥'; }
        #register-form [for="address"]::before { content: '📍'; }
        #register-form [for="contact"]::before { content: '📱'; }
        #register-form [for="appointment_date"]::before { content: '📋'; }
        #register-form [for="parent_guardian"]::before { content: '👨‍👩‍👧'; }

        #register-form label::before {
            font-size: 1.2rem;
            display: inline-block;
            flex-shrink: 0;
        }

        .form-group input,
        .form-group select,
        .login-form-group input,
        .admin-form-group input {
            width: 100%;
            padding: 0.95rem 1.1rem;
            border-radius: 14px;
            border: 2px solid rgba(43, 143, 144, 0.1);
            background: rgba(255, 255, 255, 0.92);
            font: inherit;
            color: var(--text-dark);
            transition: all 280ms cubic-bezier(0.2, 0.9, 0.2, 1);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .form-group input::placeholder,
        .form-group select::placeholder {
            color: rgba(95, 112, 116, 0.5);
        }

        #register-form input,
        #register-form select {
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.98), rgba(66, 212, 222, 0.04));
        }

        #register-form input:hover,
        #register-form select:hover {
            border-color: rgba(43, 143, 144, 0.2);
            background: linear-gradient(to bottom right, rgba(255, 255, 255, 1), rgba(66, 212, 222, 0.06));
        }

        .form-group input:focus,
        .form-group select:focus,
        .login-form-group input:focus,
        .admin-form-group input:focus {
            outline: none;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 6px rgba(43, 143, 144, 0.15), 0 8px 20px rgba(43, 143, 144, 0.12);
            background-color: #fff;
            transform: translateY(-1px);
        }

        .form-group.row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .modal-login-link,
        .login-signup-link {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.92rem;
            margin: 1rem 0 0;
            padding-top: 0.75rem;
        }

        .modal-login-link a,
        .login-signup-link a {
            font-weight: 700;
            text-decoration: none;
            color: var(--primary);
            transition: color 200ms ease;
        }

        .modal-login-link a:hover,
        .login-signup-link a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        .modal-buttons,
        .login-buttons,
        .admin-buttons {
            display: flex;
            gap: 0.85rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(43, 143, 144, 0.1);
        }

        .modal-button,
        .login-button,
        .admin-button {
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

        .modal-button.submit,
        .login-button.submit,
        .admin-button.submit {
            color: white;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            box-shadow: 0 12px 28px rgba(43, 143, 144, 0.22);
            position: relative;
            overflow: hidden;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .modal-button.submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            opacity: 0;
            transition: opacity 300ms ease;
        }

        .modal-button.submit:hover,
        .login-button.submit:hover,
        .admin-button.submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(43, 143, 144, 0.28);
        }

        .modal-button.submit:hover::before {
            opacity: 1;
        }

        .modal-button.submit:active,
        .login-button.submit:active,
        .admin-button.submit:active {
            transform: translateY(0);
            box-shadow: 0 8px 16px rgba(43, 143, 144, 0.18);
        }

        .modal-button.close,
        .login-button.close,
        .admin-button.close {
            background: rgba(43, 143, 144, 0.08);
            color: var(--text-dark);
            border: 1.5px solid rgba(43, 143, 144, 0.2);
        }

        .modal-button.close:hover,
        .login-button.close:hover,
        .admin-button.close:hover {
            background: rgba(43, 143, 144, 0.12);
            border-color: rgba(43, 143, 144, 0.3);
        }

        .admin-security-note,
        .admin-feedback {
            border-radius: 12px;
            padding: 0.9rem 1rem;
            font-size: 0.92rem;
            line-height: 1.5;
        }

        .admin-security-note {
            background: rgba(43, 143, 144, 0.08);
            border: 1px solid rgba(43, 143, 144, 0.16);
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 1rem;
        }

        .admin-feedback { margin-bottom: 1rem; }
        .admin-feedback.success { background: rgba(80, 200, 120, 0.12); color: #23653c; border: 1px solid rgba(80, 200, 120, 0.2); }
        .admin-feedback.error { background: rgba(216, 77, 77, 0.1); color: #7d2b2b; border: 1px solid rgba(216, 77, 77, 0.2); }

        .admin-step { display: block; }

        .admin-button-spinner {
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-top-color: #fff;
            display: none;
            margin-right: 0.55rem;
            animation: adminSpin 0.8s linear infinite;
            vertical-align: middle;
        }

        .admin-button.submit.loading .admin-button-spinner { display: inline-block; }

        @keyframes adminSpin { to { transform: rotate(360deg); } }

        @media (max-width: 900px) {
            .hero-inner { grid-template-columns: 1fr; }
            .feature-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 640px) {
            .page { padding: 0.75rem; }
            .shell { width: calc(100% - 1rem); }
            .topbar { padding: 0.9rem; flex-direction: column; align-items: stretch; gap: 0.8rem; }
            .brand { justify-content: center; }
            .chip-link { align-self: center; }
            .hero { padding-top: 1rem; }
            .buttons, .modal-buttons, .login-buttons, .admin-buttons { flex-direction: column; }
            .feature-grid { grid-template-columns: 1fr; }
            .form-group.row { grid-template-columns: 1fr; }
            .login-modal-content,
            .admin-modal-content,
            .modal-content { padding: 1.2rem; border-radius: 20px; }
            h1 { max-width: none; }
        }
        </style>
    </head>
    <body>
        <div class="page">
            <div class="blob blob-one"></div>
            <div class="blob blob-two"></div>
            <div class="blob blob-three"></div>

            <header class="shell topbar">
                <div class="brand" style="flex-direction: row; align-items: center; gap: 0.75rem;">
                    <img src="{{ asset('logo.png') }}" alt="BicolVax Logo" style="width: 60px; height: 60px; object-fit: contain; background: transparent;">
                    <div>
                        <strong style="display: block;">BicolVax</strong>
                        <span>Animal Bite &amp; Vaccination Center</span>
                    </div>
                </div>

                @php
                    $adminLink = Route::has('login') ? route('login') : '#';
                @endphp
                <a class="chip-link" href="#" onclick="openAdminModal(); return false;" aria-label="Admin access">
                    <span aria-hidden="true">→</span>
                    <span>Admin</span>
                </a>
            </header>

            <main class="hero shell">
                <section class="hero-inner">
                    <div class="hero-copy">
                        <div class="eyebrow">Safe scheduling • Smart reminders • Better compliance</div>

                        <h1>Automated Vaccination Scheduling &amp; Reminder</h1>

                        <p class="lede">
                            Book your Anti-Rabies vaccination appointment online. Get timely reminders for every dose,
                            track the full vaccination schedule, and manage your visits with ease.
                        </p>

                        <div class="buttons">
                            <button id="openRegisterBtn" class="button primary" onclick="openRegisterModal()">
                                <span aria-hidden="true">✦</span>
                                <span>Sign Up Here</span>
                            </button>

                            <a href="#" onclick="openLoginModal(); return false;" class="button secondary">
                                <span aria-hidden="true">→</span>
                                <span>Login to Account</span>
                            </a>
                        </div>
                    </div>

                    <div class="feature-grid">
                        <article class="feature-card">
                            <svg class="feature-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect x="18" y="10" width="28" height="44" rx="7" stroke="currentColor" stroke-width="4"/>
                                <path d="M24 24H40M24 31H40M24 38H34" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                            <h2>Easy booking</h2>
                            <p>Book your vaccination appointment in just a few taps on your smartphone.</p>
                        </article>

                        <article class="feature-card">
                            <svg class="feature-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M32 54c3.2 0 6-2.3 6.6-5.4H25.4c.6 3.1 3.4 5.4 6.6 5.4ZM46 23c0-7.2-5.3-13-12-13S22 15.8 22 23c0 11-5 13-5 13h34s-5-2-5-13Z" fill="currentColor"/>
                            </svg>
                            <h2>SMS Reminder</h2>
                            <p>Receive automatic SMS notifications before each vaccination dose.</p>
                        </article>

                        <article class="feature-card">
                            <svg class="feature-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="32" cy="32" r="20" stroke="currentColor" stroke-width="4"/>
                                <path d="m22 32 7 7 14-15" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <h2>Complete Schedule</h2>
                            <p>Auto-generated schedule for Day 0, 3, 7, 14, and 21/28.</p>
                        </article>

                        <article class="feature-card">
                            <svg class="feature-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect x="20" y="8" width="24" height="48" rx="4" stroke="currentColor" stroke-width="4"/>
                                <path d="M30 48H34" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                            <h2>Mobile Friendly</h2>
                            <p>Access vaccination records anytime, anywhere on your phone.</p>
                        </article>
                    </div>

                    <div class="hero-footer">
                        <span class="mini-pill">Anti-Rabies vaccination</span>
                        <span class="mini-pill">Automated follow-up</span>
                        <span class="mini-pill">Secure patient access</span>
                    </div>
                </section>
            </main>
        </div>

        <!-- Login Modal -->
        <div class="login-modal-overlay" id="loginModal">
            <div class="login-modal-content">
                <button class="login-close-icon" onclick="closeLoginModal()">×</button>
                
                <div class="login-modal-header">
                    <h2>Welcome Back</h2>
                    <p>Login to your BicolVax Account</p>
                </div>

                <div class="login-feedback" id="loginFeedback" hidden></div>

                <form id="loginForm" onsubmit="handleLoginSubmit(event)">
                    <div id="loginCredentialsStep">
                        <div class="login-form-group">
                            <label for="login-email">Email Address *</label>
                            <input type="email" id="login-email" name="email" placeholder="your.email@example.com" required>
                        </div>

                        <div class="login-form-group">
                            <label for="login-password">Password *</label>
                            <div style="position:relative;">
                                <input type="password" id="login-password" name="password" placeholder="Enter your password" required style="width:100%; padding-right:44px;">
                                <button type="button" id="toggleLoginPassword" aria-label="Show password" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); border:none; background:transparent; cursor:pointer; font-weight:700; color:#374151;"></button>
                            </div>
                        </div>

                        <div class="login-remember">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember" style="margin: 0; font-weight: 500; cursor: pointer;">Remember me</label>
                        </div>

                        <div class="login-signup-link">
                            Don't have an account? <a href="#" onclick="closeLoginModal(); openRegisterModal(); return false;">Register here</a>
                        </div>
                    </div>

                    <div id="loginOtpStep" style="display:none;">
                        <div class="login-form-group">
                            <label for="login-otp">OTP Code *</label>
                            <input type="text" id="login-otp" name="code" inputmode="numeric" maxlength="6" placeholder="Enter the 6-digit OTP">
                        </div>

                        <div class="login-security-note">
                            We sent an OTP code to your email. Enter it here to continue.
                        </div>
                    </div>

                    <div class="login-buttons">
                        <button type="button" class="login-button close" onclick="closeLoginModal()">
                            <span>Cancel</span>
                        </button>
                        <button type="submit" class="login-button submit" id="loginActionButton">
                            <span id="loginActionLabel">Login</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Admin Login Modal -->
        <div class="admin-modal-overlay" id="adminModal">
            <div class="admin-modal-content">
                <button class="admin-close-icon" onclick="closeAdminModal()">×</button>
                
                <div class="admin-modal-header">
                    <h2>Admin Access</h2>
                    <p>Secure Administrator Login</p>
                </div>

                <div class="admin-security-note" id="adminTopNote">
                    🔐 This is a secure admin portal. Only authorized personnel can access.
                </div>

                <div class="admin-feedback" id="adminFeedback" hidden></div>

                <form id="adminLoginForm" onsubmit="handleAdminLoginSubmit(event)">
                    <div class="admin-step active" id="adminCredentialsStep">
                        <div class="admin-form-group">
                            <label for="admin-email">Admin Email *</label>
                            <input type="email" id="admin-email" name="email" value="{{ $adminEmail ?? '' }}" placeholder="admin@bicolvax.com" required>
                        </div>

                        <div class="admin-form-group">
                            <label for="admin-password">Password *</label>
                            <div style="position:relative;">
                                <input type="password" id="admin-password" name="password" placeholder="Enter admin password" required style="width:100%; padding-right:44px;">
                                <button type="button" id="toggleAdminPassword" aria-label="Show password" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); border:none; background:transparent; cursor:pointer; font-weight:700; color:#374151;"></button>
                            </div>
                        </div>
                    </div>

                    <div class="admin-step" id="adminOtpStep">
                        <div class="admin-form-group">
                            <label for="admin-otp">OTP Code *</label>
                            <input type="text" id="admin-otp" name="code" inputmode="numeric" maxlength="6" placeholder="Enter the 6-digit OTP" required>
                        </div>

                        <div class="admin-security-note">
                            The OTP was sent to the Gmail inbox linked to the admin account. Enter it here to continue.
                        </div>
                    </div>

                    <div class="admin-buttons">
                        <button type="button" class="admin-button close" onclick="closeAdminModal()">
                            <span>Cancel</span>
                        </button>
                        <button type="submit" class="admin-button submit" id="adminActionButton">
                            <span class="admin-button-spinner" id="adminButtonSpinner" aria-hidden="true"></span>
                            <span id="adminActionLabel">Access Admin Panel</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Registration Modal -->
        <div class="modal-overlay" id="registerModal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="registerModalTitle" aria-describedby="registerModalDesc" tabindex="-1">
                <button class="close-icon" onclick="closeRegisterModal()" aria-label="Close registration form">×</button>

                <div class="modal-header">
                    <h2 id="registerModalTitle">Patient Registration</h2>
                    <p id="registerModalDesc" id="registerModalDesc">
                        <span id="registerStepLabel">Step 1 of 2 — Choose your branch</span>
                    </p>
                </div>

                <!-- Step indicators -->
                <div style="display:flex; gap:0.5rem; padding:1rem 2rem 0; justify-content:center;">
                    <div id="stepDot1" style="flex:1; height:4px; border-radius:4px; background:var(--primary); transition:background 0.3s;"></div>
                    <div id="stepDot2" style="flex:1; height:4px; border-radius:4px; background:#e5e7eb; transition:background 0.3s;"></div>
                </div>

                <form id="registerForm" method="POST" action="{{ route('public.register') }}">
                    @csrf
                    <div class="form-section" id="register-form">

                        <!-- ── Step 1: Branch Selection ── -->
                        <div id="registerStepPersonal">
                            <div class="form-group" style="margin-top:1.5rem;">
                                <label for="branch_id" style="font-weight:600; font-size:1rem;">Select Your Nearest Branch *</label>
                                <p style="font-size:0.85rem; color:#6b7280; margin-bottom:0.75rem;">Choose the BicolVax branch where you'd like to receive your vaccination.</p>
                                <select id="branch_id" name="branch_id" required style="font-size:1rem; padding:0.75rem 1rem;">
                                    <option value="">— Choose a branch —</option>
                                    @foreach(\App\Models\Branch::where('is_active', true)->orderBy('name')->get() as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }} – {{ $branch->location }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="modal-login-link" style="margin-top:1rem;">
                                Already have an account? <a href="#" onclick="closeRegisterModal(); openLoginModal(); return false;">Login here</a>
                            </div>

                            <div class="modal-buttons" style="padding:1.5rem 0 0;">
                                <button type="button" class="modal-button close" onclick="closeRegisterModal()">
                                    <span>Cancel</span>
                                </button>
                                <button type="button" class="modal-button submit" id="registerContinueBtn" onclick="registerGoToStep2()">
                                    <span>Continue →</span>
                                </button>
                            </div>
                        </div>

                        <!-- ── Step 2: Personal & Account Details ── -->
                        <div id="registerStepAccount" style="display:none;">
                            <div class="form-group" style="margin-top:1rem;">
                                <label for="fullname">Full Name *</label>
                                <input type="text" id="fullname" name="fullname" placeholder="Juan Dela Cruz" required value="{{ old('fullname') }}">
                            </div>

                            <div class="form-group">
                                <label for="contact">Contact No. *</label>
                                <input type="tel" id="contact" name="contact" placeholder="0902-XXX-XXXX" inputmode="numeric" maxlength="11" required value="{{ old('contact') }}">
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" placeholder="your.email@example.com" required value="{{ old('email') }}">
                            </div>

                            <div class="form-group" style="position:relative;">
                                <label for="password">Password *</label>
                                <div style="position:relative;">
                                    <input type="password" id="password" name="password" placeholder="Create a password" required style="width:100%; padding-right:44px;">
                                    <button type="button" id="togglePassword" aria-pressed="false" aria-label="Show password" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); border:none; background:transparent; cursor:pointer; font-weight:700; color:#374151;">Show</button>
                                </div>
                            </div>

                            <div class="form-group" style="position:relative;">
                                <label for="password_confirmation">Confirm Password *</label>
                                <div style="position:relative;">
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required style="width:100%; padding-right:44px;">
                                    <button type="button" id="togglePasswordConfirm" aria-pressed="false" aria-label="Show confirm password" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); border:none; background:transparent; cursor:pointer; font-weight:700; color:#374151;">Show</button>
                                </div>
                            </div>

                            <div class="modal-login-link">
                                Already have an account? <a href="#" onclick="closeRegisterModal(); openLoginModal(); return false;">Login here</a>
                            </div>

                            <div class="modal-buttons" style="padding:1rem 0 0;">
                                <button type="button" class="modal-button close" onclick="setRegisterStep('personal')">
                                    <span>← Back</span>
                                </button>
                                <button type="submit" class="modal-button submit">
                                    <span>Complete Registration</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <script>
            // Password visibility toggles for registration modal
            document.addEventListener('DOMContentLoaded', function() {
                const pwd = document.getElementById('password');
                const pwdToggle = document.getElementById('togglePassword');
                const pwdConf = document.getElementById('password_confirmation');
                const pwdConfToggle = document.getElementById('togglePasswordConfirm');

                    // SVG icons
                    const eyeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
                    const eyeOffSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.67 21.67 0 0 1 5.06-5.94"></path><path d="M1 1l22 22"></path><path d="M9.88 9.88A3 3 0 0 0 14.12 14.12"></path></svg>';

                    function setBtnIcon(btn, shown) {
                        if (!btn) return;
                        btn.innerHTML = shown ? eyeOffSvg : eyeSvg;
                        btn.setAttribute('aria-pressed', shown ? 'true' : 'false');
                    }

                    if (pwd && pwdToggle) {
                        setBtnIcon(pwdToggle, false);
                        pwdToggle.addEventListener('click', function() {
                            const showing = pwd.type === 'text';
                            if (showing) {
                                pwd.type = 'password';
                            } else {
                                pwd.type = 'text';
                            }
                            setBtnIcon(pwdToggle, !showing);
                        });
                    }

                    if (pwdConf && pwdConfToggle) {
                        setBtnIcon(pwdConfToggle, false);
                        pwdConfToggle.addEventListener('click', function() {
                            const showing = pwdConf.type === 'text';
                            if (showing) {
                                pwdConf.type = 'password';
                            } else {
                                pwdConf.type = 'text';
                            }
                            setBtnIcon(pwdConfToggle, !showing);
                        });
                    }
            });
                // Setup eye icons for login and admin modal fields
                document.addEventListener('DOMContentLoaded', function() {
                    const lpwd = document.getElementById('login-password');
                    const lpwdToggle = document.getElementById('toggleLoginPassword');
                    const apwd = document.getElementById('admin-password');
                    const apwdToggle = document.getElementById('toggleAdminPassword');

                    const eyeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
                    const eyeOffSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.67 21.67 0 0 1 5.06-5.94"></path><path d="M1 1l22 22"></path><path d="M9.88 9.88A3 3 0 0 0 14.12 14.12"></path></svg>';

                    function setBtnIconLocal(btn, shown) {
                        if (!btn) return;
                        btn.innerHTML = shown ? eyeOffSvg : eyeSvg;
                        btn.setAttribute('aria-pressed', shown ? 'true' : 'false');
                    }

                    if (lpwd && lpwdToggle) {
                        setBtnIconLocal(lpwdToggle, false);
                        lpwdToggle.addEventListener('click', function() {
                            const showing = lpwd.type === 'text';
                            lpwd.type = showing ? 'password' : 'text';
                            setBtnIconLocal(lpwdToggle, !showing);
                        });
                    }

                    if (apwd && apwdToggle) {
                        setBtnIconLocal(apwdToggle, false);
                        apwdToggle.addEventListener('click', function() {
                            const showing = apwd.type === 'text';
                            apwd.type = showing ? 'password' : 'text';
                            setBtnIconLocal(apwdToggle, !showing);
                        });
                    }
                });
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const adminLoginUrl = @json(route('admin.login.post'));
            const adminOtpUrl = @json(route('admin.otp.verify.post'));
            const publicLoginUrl = @json(route('public.login'));
            const adminDefaultEmail = @json($adminEmail ?? '');
            let adminModalStep = 'credentials';
            let loginModalStep = 'credentials';

            function openLoginModal() {
                document.getElementById('loginModal').classList.add('active');
                document.body.style.overflow = 'hidden';
                resetLoginModalState();
            }

            function closeLoginModal() {
                document.getElementById('loginModal').classList.remove('active');
                document.body.style.overflow = 'auto';
                resetLoginModalState();
            }

            function handleLoginSubmit(event) {
                event.preventDefault();

                const actionButton = document.getElementById('loginActionButton');
                const feedback = document.getElementById('loginFeedback');

                feedback.hidden = true;

                if (loginModalStep === 'credentials') {
                    const email = document.getElementById('login-email')?.value?.trim();
                    const password = document.getElementById('login-password')?.value || '';

                    if (!email || !password) {
                        showLoginFeedback('Please enter your email and password.');
                        return;
                    }

                    setLoginButtonLoading(true, 'Sending OTP...');

                    fetch(publicLoginUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ email, password }),
                    })
                        .then(async (response) => {
                            const data = await response.json().catch(() => ({}));

                            if (!response.ok) {
                                throw new Error(data.message || (data.errors && Object.values(data.errors).flat()[0]) || 'Login failed');
                            }

                            if (data.next === 'otp') {
                                setLoginStep('otp');
                                showLoginFeedback(data.message || 'OTP sent to your email.', 'success');
                                return;
                            }

                            if (data.redirect) {
                                window.location.href = data.redirect;
                                return;
                            }

                            throw new Error(data.message || 'Login failed');
                        })
                        .catch((err) => {
                            showLoginFeedback(err.message || 'Login failed');
                            setLoginButtonLoading(false, 'Login');
                        })
                        .finally(() => {
                            if (loginModalStep === 'otp') {
                                setLoginButtonLoading(false, 'Verify OTP');
                            }
                        });

                    return;
                }

                if (loginModalStep === 'otp') {
                    const code = document.getElementById('login-otp')?.value?.trim();

                    if (!code) {
                        showLoginFeedback('Please enter the OTP code sent to your email.');
                        return;
                    }

                    setLoginButtonLoading(true, 'Verifying OTP...');

                    fetch(publicLoginUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ code }),
                    })
                        .then(async (response) => {
                            const data = await response.json().catch(() => ({}));

                            if (!response.ok) {
                                throw new Error(data.message || (data.errors && Object.values(data.errors).flat()[0]) || 'Login failed');
                            }

                            window.location.href = data.redirect || @json(route('user.dashboard'));
                        })
                        .catch((err) => {
                            showLoginFeedback(err.message || 'Login failed');
                            setLoginButtonLoading(false, 'Verify OTP');
                        });

                    return;
                }

                fetch(publicLoginUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ email, password }),
                })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({}));
                        if (response.ok) {
                            // redirect to user dashboard
                            window.location.href = data.redirect || @json(route('user.dashboard'));
                        } else {
                            throw new Error(data.message || (data.errors && Object.values(data.errors).flat()[0]) || 'Login failed');
                        }
                    })
                    .catch((err) => {
                        alert(err.message || 'Login failed');
                    });
            }

            function setLoginStep(step) {
                loginModalStep = step;

                const credentialsStep = document.getElementById('loginCredentialsStep');
                const otpStep = document.getElementById('loginOtpStep');
                const actionLabel = document.getElementById('loginActionLabel');

                if (step === 'otp') {
                    credentialsStep.style.display = 'none';
                    otpStep.style.display = 'block';
                    actionLabel.textContent = 'Verify OTP';
                    document.getElementById('login-email').required = false;
                    document.getElementById('login-password').required = false;
                    document.getElementById('login-otp').required = true;
                    document.getElementById('login-otp').focus();
                    return;
                }

                credentialsStep.style.display = 'block';
                otpStep.style.display = 'none';
                actionLabel.textContent = 'Login';
                document.getElementById('login-email').required = true;
                document.getElementById('login-password').required = true;
                document.getElementById('login-otp').required = false;
            }

            function setLoginButtonLoading(isLoading, label) {
                const actionButton = document.getElementById('loginActionButton');
                const actionLabel = document.getElementById('loginActionLabel');

                actionButton.disabled = isLoading;
                actionLabel.textContent = label;
            }

            function showLoginFeedback(message, type = 'error') {
                const feedback = document.getElementById('loginFeedback');

                feedback.textContent = message;
                feedback.className = `login-feedback ${type}`;
                feedback.hidden = false;
            }

            function resetLoginModalState() {
                setLoginStep('credentials');
                setLoginButtonLoading(false, 'Login');

                const feedback = document.getElementById('loginFeedback');
                const emailField = document.getElementById('login-email');
                const passwordField = document.getElementById('login-password');
                const otpField = document.getElementById('login-otp');

                feedback.hidden = true;
                feedback.textContent = '';
                emailField.value = '';
                passwordField.value = '';
                otpField.value = '';
            }

            function setAdminStep(step) {
                adminModalStep = step;

                const credentialsStep = document.getElementById('adminCredentialsStep');
                const otpStep = document.getElementById('adminOtpStep');
                const actionLabel = document.getElementById('adminActionLabel');
                const actionButton = document.getElementById('adminActionButton');
                const headerTitle = document.querySelector('.admin-modal-header h2');
                const headerSubtitle = document.querySelector('.admin-modal-header p');
                const primaryNote = document.getElementById('adminTopNote');

                if (step === 'otp') {
                    credentialsStep.classList.remove('active');
                    otpStep.classList.add('active');
                    actionLabel.textContent = 'Verify OTP';
                    actionButton.disabled = false;
                    document.getElementById('admin-email').required = false;
                    document.getElementById('admin-password').required = false;
                    document.getElementById('admin-otp').required = true;
                    document.getElementById('admin-email').disabled = true;
                    document.getElementById('admin-password').disabled = true;
                    document.getElementById('admin-otp').disabled = false;
                    headerTitle.textContent = 'OTP Verification';
                    headerSubtitle.textContent = 'Enter the code sent to your Gmail inbox';
                    if (primaryNote) {
                        primaryNote.textContent = '📩 We sent a one-time code to the admin email. Enter it below to continue.';
                    }
                    document.getElementById('admin-otp').focus();
                    return;
                }

                credentialsStep.classList.add('active');
                otpStep.classList.remove('active');
                actionLabel.textContent = 'Access Admin Panel';
                actionButton.disabled = false;
                document.getElementById('admin-email').required = true;
                document.getElementById('admin-password').required = true;
                document.getElementById('admin-otp').required = false;
                document.getElementById('admin-email').disabled = false;
                document.getElementById('admin-password').disabled = false;
                document.getElementById('admin-otp').disabled = true;
                headerTitle.textContent = 'Admin Access';
                headerSubtitle.textContent = 'Secure Administrator Login';
                if (primaryNote) {
                    primaryNote.textContent = '🔐 This is a secure admin portal. Only authorized personnel can access.';
                }
            }

            function setAdminButtonLoading(isLoading, label) {
                const actionButton = document.getElementById('adminActionButton');
                const spinner = document.getElementById('adminButtonSpinner');
                const actionLabel = document.getElementById('adminActionLabel');

                actionButton.disabled = isLoading;
                actionButton.classList.toggle('loading', isLoading);
                spinner.hidden = !isLoading;
                actionLabel.textContent = label;
            }

            function showAdminFeedback(message, type = 'error') {
                const feedback = document.getElementById('adminFeedback');

                feedback.textContent = message;
                feedback.className = `admin-feedback ${type}`;
                feedback.hidden = false;
            }

            function resetAdminModalState() {
                setAdminStep('credentials');
                setAdminButtonLoading(false, 'Access Admin Panel');

                const feedback = document.getElementById('adminFeedback');
                const emailField = document.getElementById('admin-email');
                const passwordField = document.getElementById('admin-password');
                const otpField = document.getElementById('admin-otp');

                feedback.hidden = true;
                feedback.textContent = '';
                emailField.value = adminDefaultEmail;
                passwordField.value = '';
                otpField.value = '';
            }

            function openAdminModal() {
                document.getElementById('adminModal').classList.add('active');
                document.body.style.overflow = 'hidden';
                resetAdminModalState();
            }

            function closeAdminModal() {
                document.getElementById('adminModal').classList.remove('active');
                document.body.style.overflow = 'auto';
                resetAdminModalState();
            }

            function handleAdminLoginSubmit(event) {
                event.preventDefault();

                const actionButton = document.getElementById('adminActionButton');
                const feedback = document.getElementById('adminFeedback');

                feedback.hidden = true;

                if (adminModalStep === 'credentials') {
                    const email = document.getElementById('admin-email').value.trim();
                    const password = document.getElementById('admin-password').value;

                    if (!email || !password) {
                        showAdminFeedback('Please fill in the admin email and password.');
                        return;
                    }

                    setAdminStep('otp');
                    setAdminButtonLoading(true, 'Sending OTP...');
                    showAdminFeedback('Sending OTP to your Gmail inbox...', 'success');

                    fetch(adminLoginUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ email, password }),
                    })
                        .then(async (response) => {
                            const data = await response.json().catch(() => ({}));

                            if (!response.ok) {
                                throw new Error(data.message || 'Admin login failed.');
                            }

                            showAdminFeedback(data.message || 'OTP sent to your email.', 'success');
                        })
                        .catch((error) => {
                            showAdminFeedback(error.message || 'Admin login failed.');
                            setAdminStep('credentials');
                            setAdminButtonLoading(false, 'Access Admin Panel');
                        })
                        .finally(() => {
                            if (adminModalStep === 'otp') {
                                setAdminButtonLoading(false, 'Verify OTP');
                            }
                        });

                    return;
                }

                if (adminModalStep === 'otp') {
                    const code = document.getElementById('admin-otp').value.trim();

                    if (!code) {
                        showAdminFeedback('Please enter the OTP code sent to your Gmail inbox.');
                        return;
                    }

                    setAdminButtonLoading(true, 'Verifying OTP...');

                    fetch(adminOtpUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ code }),
                    })
                        .then(async (response) => {
                            const data = await response.json().catch(() => ({}));

                            if (!response.ok) {
                                throw new Error(data.message || 'OTP verification failed.');
                            }

                            window.location.href = data.redirect || @json(route('admin.dashboard'));
                        })
                        .catch((error) => {
                            showAdminFeedback(error.message || 'OTP verification failed.');
                            setAdminButtonLoading(false, 'Verify OTP');
                        });
                }
            }

            function openRegisterModal() {
                const modal = document.getElementById('registerModal');
                if (!modal) return;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                setRegisterStep('personal');
            }

            function closeRegisterModal() {
                const modal = document.getElementById('registerModal');
                if (!modal) return;
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }

            function setRegisterStep(step) {
                const personal = document.getElementById('registerStepPersonal');
                const account = document.getElementById('registerStepAccount');
                const stepLabel = document.getElementById('registerStepLabel');
                const dot1 = document.getElementById('stepDot1');
                const dot2 = document.getElementById('stepDot2');
                if (!personal || !account) return;

                if (step === 'account') {
                    personal.style.display = 'none';
                    account.style.display = 'block';
                    if (stepLabel) stepLabel.textContent = 'Step 2 of 2 — Your details';
                    if (dot1) dot1.style.background = 'var(--primary)';
                    if (dot2) dot2.style.background = 'var(--primary)';
                    const fullname = document.getElementById('fullname');
                    if (fullname) fullname.focus();
                    return;
                }

                personal.style.display = 'block';
                account.style.display = 'none';
                if (stepLabel) stepLabel.textContent = 'Step 1 of 2 — Choose your branch';
                if (dot1) dot1.style.background = 'var(--primary)';
                if (dot2) dot2.style.background = '#e5e7eb';
                const branchSelect = document.getElementById('branch_id');
                if (branchSelect) branchSelect.focus();
            }

            function registerGoToStep2() {
                const branchSelect = document.getElementById('branch_id');
                if (!branchSelect || !branchSelect.value) {
                    branchSelect.focus();
                    branchSelect.style.border = '2px solid #e3342f';
                    branchSelect.addEventListener('change', function() {
                        branchSelect.style.border = '';
                    }, { once: true });
                    return;
                }
                setRegisterStep('account');
            }

            // Basic client-side validation: ensure passwords match before submitting
                document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('registerForm');
                if (!form) return;

                form.addEventListener('submit', function (e) {
                    const pw = document.getElementById('password');
                    const conf = document.getElementById('password_confirmation');
                    if (pw && conf && pw.value !== conf.value) {
                        e.preventDefault();
                        alert('Passwords do not match. Please confirm your password.');
                        conf.focus();
                        return false;
                    }
                    return true;
                });

                // Auto-fill age when birthday is entered
                const birthday = document.getElementById('birthday');
                const ageInput = document.getElementById('age');
                function updateAgeFromBirthday() {
                    if (!birthday || !ageInput) return;
                    const val = birthday.value;
                    if (!val) {
                        ageInput.value = '';
                        return;
                    }
                    const b = new Date(val + 'T00:00:00');
                    const today = new Date();
                    let age = today.getFullYear() - b.getFullYear();
                    const m = today.getMonth() - b.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < b.getDate())) {
                        age--;
                    }
                    if (age < 0 || Number.isNaN(age)) {
                        ageInput.value = '';
                    } else {
                        ageInput.value = age;
                    }
                }

                if (birthday) {
                    birthday.addEventListener('change', updateAgeFromBirthday);
                    birthday.addEventListener('input', updateAgeFromBirthday);
                    // initialize if prefilled
                    updateAgeFromBirthday();
                }
            });

            let _lastFocusedElement = null;

            function _getFocusableElements(container) {
                return Array.from(container.querySelectorAll('a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'))
                    .filter(el => el.offsetWidth > 0 || el.offsetHeight > 0 || el === document.activeElement);
            }

            function _trapFocus(modal) {
                const focusable = _getFocusableElements(modal);
                if (focusable.length === 0) return null;
                const first = focusable[0];
                const last = focusable[focusable.length - 1];

                function handler(e) {
                    if (e.key === 'Tab') {
                        if (e.shiftKey && document.activeElement === first) {
                            e.preventDefault();
                            last.focus();
                        } else if (!e.shiftKey && document.activeElement === last) {
                            e.preventDefault();
                            first.focus();
                        }
                    }
                }

                document.addEventListener('keydown', handler);
                return handler;
            }

            function _releaseTrap(handler) {
                if (!handler) return;
                document.removeEventListener('keydown', handler);
            }

            function openRegisterModal() {
                const modal = document.getElementById('registerModal');
                _lastFocusedElement = document.activeElement;
                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';

                // always start at step 1 (branch selection)
                setRegisterStep('personal');

                const firstInput = modal.querySelector('input, select, textarea, button');
                if (firstInput) firstInput.focus();

                // attach focus trap
                modal._focusHandler = _trapFocus(modal);
            }

            function closeRegisterModal() {
                const modal = document.getElementById('registerModal');
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = 'auto';

                // release focus trap
                _releaseTrap(modal._focusHandler);
                modal._focusHandler = null;

                // return focus to opener
                try { if (_lastFocusedElement) _lastFocusedElement.focus(); } catch (e) {}
            }

            // Close modal when clicking outside the content
            document.getElementById('registerModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeRegisterModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeRegisterModal();
                    closeLoginModal();
                    closeAdminModal();
                }
            });

            // Close login modal when clicking outside the content
            document.getElementById('loginModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeLoginModal();
                }
            });

            // Close admin modal when clicking outside the content
            document.getElementById('adminModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeAdminModal();
                }
            });

            // Enforce digits-only and max length for contact input
            (function() {
                const contact = document.getElementById('contact');
                if (!contact) return;

                // Remove non-digits on input and enforce max 11
                contact.addEventListener('input', function() {
                    const cleaned = this.value.replace(/\D+/g, '').slice(0, 11);
                    if (this.value !== cleaned) this.value = cleaned;
                });

                // Prevent non-digit key presses
                contact.addEventListener('keypress', function(e) {
                    const char = String.fromCharCode(e.which || e.keyCode);
                    if (!/[0-9]/.test(char)) {
                        e.preventDefault();
                    }
                });

                // Handle paste: only insert digits up to 11
                contact.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = paste.replace(/\D+/g, '').slice(0, 11 - this.value.length);
                    if (digits.length) {
                        const start = this.selectionStart || this.value.length;
                        const end = this.selectionEnd || this.value.length;
                        this.value = this.value.slice(0, start) + digits + this.value.slice(end);
                        const caret = start + digits.length;
                        this.setSelectionRange(caret, caret);
                        this.dispatchEvent(new Event('input'));
                    }
                });
            })();

            // Set minimum date for preferred appointment to today
            (function() {
                const appt = document.getElementById('appointment_date');
                if (!appt) return;
                const today = new Date().toISOString().split('T')[0];
                appt.setAttribute('min', today);
            })();

            // Show server-side flash success message (e.g., after registration)
            (function() {
                const successMessage = @json(session('success'));
                if (successMessage) {
                    // close registration modal if open
                    try { closeRegisterModal(); } catch (e) {}

                    // create toast/banner
                    const banner = document.createElement('div');
                    banner.style.position = 'fixed';
                    banner.style.right = '1rem';
                    banner.style.top = '1rem';
                    banner.style.zIndex = 1200;
                    banner.style.background = 'linear-gradient(135deg,#2b8f90,#42d4de)';
                    banner.style.color = 'white';
                    banner.style.padding = '0.85rem 1rem';
                    banner.style.borderRadius = '10px';
                    banner.style.boxShadow = '0 12px 28px rgba(43,143,144,0.18)';
                    banner.style.fontWeight = 700;
                    banner.textContent = successMessage;

                    document.body.appendChild(banner);

                    // auto-dismiss after 6 seconds
                    setTimeout(() => banner.remove(), 6000);
                }
            })();

            // Show server-side validation errors (if any) and re-open the registration modal
            (function() {
                const errors = @json($errors->any() ? $errors->all() : []);
                if (errors && errors.length) {
                    // open registration modal so user sees the form with old values
                    try {
                        openRegisterModal();
                        // If branch was already selected, jump to step 2 so user sees their field errors
                        const oldBranchId = @json(old('branch_id'));
                        if (oldBranchId) {
                            setRegisterStep('account');
                        }
                    } catch (e) {}

                    const banner = document.createElement('div');
                    banner.style.position = 'fixed';
                    banner.style.right = '1rem';
                    banner.style.top = '1rem';
                    banner.style.zIndex = 1200;
                    banner.style.background = '#f44336';
                    banner.style.color = 'white';
                    banner.style.padding = '0.85rem 1rem';
                    banner.style.borderRadius = '10px';
                    banner.style.boxShadow = '0 12px 28px rgba(0,0,0,0.12)';

                    const title = document.createElement('div');
                    title.style.fontWeight = '800';
                    title.style.marginBottom = '0.35rem';
                    title.textContent = 'Please fix the following errors:';
                    banner.appendChild(title);

                    const list = document.createElement('ul');
                    list.style.margin = '0';
                    list.style.paddingLeft = '1.1rem';
                    list.style.fontWeight = '700';
                    errors.forEach(err => {
                        const li = document.createElement('li');
                        li.textContent = err;
                        list.appendChild(li);
                    });
                    banner.appendChild(list);

                    document.body.appendChild(banner);

                    // auto-dismiss after 8 seconds
                    setTimeout(() => banner.remove(), 8000);
                }
            })();
        </script>
    </body>
</html>
 