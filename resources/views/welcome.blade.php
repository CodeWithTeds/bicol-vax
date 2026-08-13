<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BicolVax | Animal Bite & Vaccination Center</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:600,700,800|plus-jakarta-sans:400,500,600,700" rel="stylesheet" />
    <style>
        :root {
            --primary: #2b8f90;
            --primary-light: #42d4de;
            --primary-soft: rgba(43, 143, 144, 0.12);
            --text-dark: #0f2d31;
            --text-muted: #5f7074;
            --border: rgba(15, 45, 49, 0.12);
            --bg: #f5f0cc;
            --navbar-bg: #b8962e;
            --card-bg: #6fa89a;
        }

        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
            color: #1a1a1a;
            background: var(--bg);
            overflow-x: hidden;
        }

        a { color: var(--primary); text-decoration: none; }

        /* NAVBAR */
        .navbar {
            background: var(--navbar-bg);
            padding: 0.85rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand { display: flex; align-items: center; gap: 0.75rem; }

        .navbar-brand img {
            width: 56px; height: 56px;
            object-fit: contain;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .navbar-brand-text strong { display: block; font-size: 1.3rem; font-weight: 700; color: #fff; }
        .navbar-brand-text span { font-size: 0.82rem; color: rgba(255,255,255,0.8); }

        .navbar-links {
            display: flex; align-items: center; gap: 2.5rem;
            list-style: none; margin: 0; padding: 0;
        }

        .navbar-links a {
            color: rgba(255,255,255,0.92);
            font-weight: 600; font-size: 0.92rem;
            letter-spacing: 0.5px; text-transform: uppercase;
            transition: color 180ms ease;
        }

        .navbar-links a:hover, .navbar-links a.active {
            color: #fff;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        /* HERO */
        .hero {
            text-align: center;
            padding: 3rem 1.5rem 2rem;
            max-width: 820px;
            margin: 0 auto;
        }

        .hero h1 {
            font-family: "Fraunces", serif;
            font-size: clamp(2.4rem, 4.5vw, 3.6rem);
            font-weight: 800;
            line-height: 1.15;
            color: #1a1a1a;
            margin: 0 0 1rem;
        }

        .hero p {
            font-size: 1.1rem; color: #333;
            line-height: 1.7; margin: 0;
        }

        /* PHOTO GALLERY */
        .photo-gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            max-width: 1060px;
            margin: 0 auto 2rem;
            padding: 0 1.5rem;
        }

        .photo-gallery-item {
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 4px 6px 18px rgba(0,0,0,0.18);
            aspect-ratio: 4/3;
            background: #ddd;
        }

        .photo-gallery-item img {
            width: 100%; height: 100%; object-fit: cover; display: block;
            transition: transform 400ms cubic-bezier(0.2,0.9,0.2,1);
        }

        .photo-gallery-item:hover img { transform: scale(1.04); }

        /* FEATURE CARDS */
        .features {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            max-width: 1060px;
            margin: 0 auto 2rem;
            padding: 0 1.5rem;
        }

        .feature-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 1.75rem 1.25rem;
            text-align: center;
        }

        .feature-icon { width: 44px; height: 44px; margin: 0 auto 0.85rem; color: #1a1a1a; }
        .feature-card h3 { font-size: 1rem; font-weight: 700; margin: 0 0 0.5rem; color: #1a1a1a; }
        .feature-card p { font-size: 0.9rem; line-height: 1.6; margin: 0; color: #1a2a2a; }

        /* CTA BUTTONS */
        .cta-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            min-width: 140px; min-height: 38px;
            padding: 0.6rem 1.2rem;
            border-radius: 8px; border: 2px solid transparent;
            font: inherit; font-weight: 700; font-size: 0.88rem;
            cursor: pointer;
            transition: transform 180ms ease, box-shadow 180ms ease;
        }
        .cta-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 6px 16px rgba(43,143,144,0.28);
        }
        .cta-primary:hover { transform: translateY(-2px); }
        .cta-secondary {
            background: transparent;
            color: var(--primary);
            border-color: var(--primary);
        }
        .cta-secondary:hover { background: rgba(43,143,144,0.08); transform: translateY(-2px); }

        @media (max-width: 900px) {
            .features { grid-template-columns: repeat(2, 1fr); }
            .photo-gallery { grid-template-columns: repeat(2, 1fr); }
            .photo-gallery-item:last-child { grid-column: span 2; }
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

        /* Terms/Privacy modal must sit above the login & registration modals */
        #termsModal {
            z-index: 1001;
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

        @media (max-width: 640px) {
            .form-group input,
            .form-group select {
                padding: 0.85rem 0.9rem;
                font-size: 0.95rem;
                border-radius: 12px;
            }

            #registerModal .modal-content {
                max-height: 94vh;
                border-radius: 16px;
            }

            #registerModal .modal-header {
                padding: 1.1rem 1.25rem !important;
                margin: -1.5rem -1.25rem 1.25rem -1.25rem !important;
            }

            #registerModal .modal-buttons {
                flex-direction: column;
            }

            #registerModal .modal-button {
                flex: none;
                width: 100%;
                min-height: 48px;
            }

            #register-form {
                padding: 1.25rem 0.25rem !important;
            }

            #register-form .form-group.row {
                gap: 0.9rem;
            }

            #registerModal .modal-content .close-icon {
                top: 0.7rem;
                right: 0.7rem;
            }
        }

        @media (max-width: 400px) {
            .modal-content { padding: 1.25rem; }
            #registerModal .modal-header h2 { font-size: 1.25rem !important; }
            .form-group label { font-size: 0.9rem; }
            .form-group input,
            .form-group select { padding: 0.8rem 0.85rem; font-size: 0.92rem; }
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
            .navbar { padding: 0.75rem 1rem; flex-direction: column; gap: 0.75rem; }
            .navbar-links { gap: 1.25rem; flex-wrap: wrap; justify-content: center; }
            .features { grid-template-columns: 1fr 1fr; }
            .photo-gallery { grid-template-columns: 1fr; }
            .photo-gallery-item:last-child { grid-column: span 1; }
            .form-group.row { grid-template-columns: 1fr; }
            .modal-buttons, .login-buttons, .admin-buttons { flex-direction: column; }
            .login-modal-content, .admin-modal-content, .modal-content { padding: 1.2rem; border-radius: 20px; }
        }
        </style>
    </head>
    <body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="{{ asset('logo.png') }}" alt="BicolVax Logo">
            <div class="navbar-brand-text">
                <strong>BicolVax</strong>
                <span>Animal Bite &amp; Vaccination Center</span>
            </div>
        </div>
        <ul class="navbar-links">
            <li><a href="#" class="active">HOME</a></li>
            <li><a href="#">ABOUT US</a></li>
            <li><a href="#">FAQs</a></li>
            <li><a href="#" onclick="openAdminModal(); return false;">CONTACT US</a></li>
            <li><a href="#" onclick="openLoginModal(); return false;" style="background:rgba(255,255,255,0.18); padding:0.35rem 0.9rem; border-radius:6px;">LOGIN</a></li>
        </ul>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <h1>Automated Vaccination<br>Scheduling &amp; Reminder</h1>
        <p>Book your Anti-Rabies vaccination appointment online. Get Automated<br>reminder for your complete vaccination schedule.</p>
        <button class="signup-btn" onclick="openRegisterModal()" style="margin-top:1rem;">SIGN UP NOW</button>
    </section>

    <!-- PHOTO GALLERY -->
    <div class="photo-gallery">
        <div class="photo-gallery-item">
            <img src="{{ asset('images/hover1.png') }}" alt="BicolVax clinic waiting area" loading="lazy">
        </div>
        <div class="photo-gallery-item">
            <img src="{{ asset('images/hover2.png') }}" alt="BicolVax clinic consultation area" loading="lazy">
        </div>
        <div class="photo-gallery-item">
            <img src="{{ asset('images/hover3.png') }}" alt="BicolVax clinic entrance" loading="lazy">
        </div>
    </div>

    <!-- FEATURE CARDS -->
    <div class="features">
        <div class="feature-card">
            <svg class="feature-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="18" y="10" width="28" height="44" rx="7" stroke="currentColor" stroke-width="4"/>
                <path d="M24 24H40M24 31H40M24 38H34" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
            </svg>
            <h3>Easy booking</h3>
            <p>Book your vaccination appointment in just a few taps on your smartphone</p>
        </div>
        <div class="feature-card">
            <svg class="feature-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M32 54c3.2 0 6-2.3 6.6-5.4H25.4c.6 3.1 3.4 5.4 6.6 5.4ZM46 23c0-7.2-5.3-13-12-13S22 15.8 22 23c0 11-5 13-5 13h34s-5-2-5-13Z" fill="currentColor"/>
            </svg>
            <h3>Email Reminder</h3>
            <p>Receive automatic email notifications before each vaccination dose.</p>
        </div>
        <div class="feature-card">
            <svg class="feature-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="32" cy="32" r="20" stroke="currentColor" stroke-width="4"/>
                <path d="m22 32 7 7 14-15" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3>Complete Schedule</h3>
            <p>Auto-generated vaccination schedule: Day 0, 3, 7, 14, and 21/28</p>
        </div>
        <div class="feature-card">
            <svg class="feature-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="32" cy="32" r="20" stroke="currentColor" stroke-width="4"/>
                <path d="M32 22v10l6 4" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3>Clinic Hours</h3>
            <p>Clinic Hours:<br>Monday - Saturday<br>8:00 am - 5:00 pm</p>
        </div>
    </div>

    <!-- HOW IT WORKS STEPS -->
    <div class="steps-section">
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Register &amp; Fill Form</h3>
                <p>Create your account and complete the animal bite information form</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Book Appointment</h3>
                <p>Select your preferred date and time for your first vaccination</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Get a Reminders</h3>
                <p>Receive email reminders for all your scheduled vaccination dates</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="site-footer">
        <strong>BicolVax – Baao</strong><br>
        Animal Bite &amp; Vaccination Center<br>
        &copy; 2026 BicolVax. All rights reserved.
    </footer>

    <style>
        .agree-row {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            font-weight: 600;
            font-size: 0.92rem;
        }

        .agree-row input[type="checkbox"] {
            width: 17px; height: 17px; cursor: pointer;
            accent-color: var(--primary);
        }

        .signup-btn {
            background: var(--primary);
            color: #f5f0cc;
            border: none;
            border-radius: 8px;
            padding: 0.85rem 3.5rem;
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background 200ms ease, transform 180ms ease;
        }

        .signup-btn:hover { background: #237778; transform: translateY(-2px); }
        .signup-btn:disabled { opacity: 0.45; cursor: not-allowed; transform: none; }

        /* STEPS SECTION */
        .steps-section {
            background: #e8eeea;
            padding: 2rem 1.5rem;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }

        .step-card { padding: 0.5rem; }

        .step-number {
            width: 50px; height: 50px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            font-size: 1.25rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
        }

        .step-card h3 { font-size: 1rem; font-weight: 700; margin: 0 0 0.4rem; }
        .step-card p { font-size: 0.85rem; color: #3a4a3a; line-height: 1.55; margin: 0; }

        /* FOOTER */
        .site-footer {
            background: var(--navbar-bg);
            color: rgba(255,255,255,0.88);
            text-align: center;
            padding: 1.25rem 1rem;
            font-size: 0.85rem;
            line-height: 1.8;
        }

        .site-footer strong { color: #fff; font-size: 0.92rem; }

        @media (max-width: 640px) {
            .steps-grid { grid-template-columns: 1fr; }
        }
    </style>

        <!-- Login Modal -->
        <!-- ═══ LOGIN MODAL ═══ -->
        <div class="login-modal-overlay" id="loginModal">
            <div class="login-modal-content">
                <button class="login-close-icon" onclick="closeLoginModal()" aria-label="Close">×</button>

                <!-- User icon -->
                <div style="text-align:center; margin-bottom:0.5rem;">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="24" cy="17" r="9" stroke="#1a1a1a" stroke-width="2.5"/>
                        <path d="M6 40c0-9.94 8.06-18 18-18s18 8.06 18 18" stroke="#1a1a1a" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>

                <h2 class="lm-title">Sign Up</h2>

                <div class="login-feedback" id="loginFeedback" hidden></div>

                <form id="loginForm" onsubmit="handleLoginSubmit(event)">
                    <div id="loginCredentialsStep">
                        <div class="lm-group">
                            <label class="lm-label" for="login-email">Email Address*</label>
                            <input class="lm-input" type="email" id="login-email" name="email" placeholder="your.email@example.com" required>
                        </div>

                        <div class="lm-group">
                            <label class="lm-label" for="login-password">Password*</label>
                            <div style="position:relative;">
                                <input class="lm-input" type="password" id="login-password" name="password" placeholder="••••••••••" required style="width:100%; padding-right:44px;">
                                <button type="button" id="toggleLoginPassword" aria-label="Show password" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); border:none; background:transparent; cursor:pointer; color:#555;"></button>
                            </div>
                        </div>

                        <div class="lm-agree-row">
                            <input type="checkbox" id="loginAgree" style="width:16px;height:16px;accent-color:#2b8f90;cursor:pointer;">
                            <label for="loginAgree" style="cursor:pointer; font-size:0.82rem; color:#333;">Your data will be store in a centralized database. I haved read and agree to the <a href="#" onclick="openTermsModal('terms'); return false;" style="color:#2b8f90; text-decoration:underline; font-weight:600;">terms</a>, condition and <a href="#" onclick="openTermsModal('privacy'); return false;" style="color:#2b8f90; text-decoration:underline; font-weight:600;">privacy policy</a>.</label>
                        </div>
                    </div>

                    <div id="loginOtpStep" style="display:none;">
                        <div class="lm-group">
                            <label class="lm-label" for="login-otp">OTP Code *</label>
                            <input class="lm-input" type="text" id="login-otp" name="code" inputmode="numeric" maxlength="6" placeholder="Enter the 6-digit OTP">
                        </div>
                        <div class="login-security-note" style="font-size:0.82rem; margin-top:0.5rem;">
                            We sent an OTP code to your email. Enter it here to continue.
                        </div>
                    </div>

                    <button type="submit" class="lm-submit-btn" id="loginActionButton">
                        <span id="loginActionLabel">Log In</span>
                    </button>

                    <div class="lm-or-row">
                        <span></span>
                        <span class="lm-or-text">Or</span>
                        <span></span>
                    </div>

                    <button type="button" class="lm-google-btn" onclick="alert('Google sign-in coming soon.')">
                        <svg width="20" height="20" viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/><path fill="none" d="M0 0h48v48H0z"/></svg>
                        Sign In with Google
                    </button>

                    <div style="text-align:center; margin-top:0.75rem; font-size:0.82rem; color:#555;">
                        Don't have an account? <a href="#" onclick="closeLoginModal(); openRegisterModal(); return false;" style="color:#2b8f90; font-weight:700;">Register here</a>
                    </div>
                </form>
            </div>
        </div>

        <style>
            /* Login modal — new design */
            .login-modal-overlay {
                position: fixed; inset: 0; z-index: 999;
                display: none; align-items: center; justify-content: center;
                padding: 0.75rem;
                background: url('{{ asset("images/background-login.png") }}') center/cover no-repeat;
            }
            .login-modal-overlay::before {
                content: ''; position: absolute; inset: 0;
                background: rgba(0,0,0,0.45);
            }
            .login-modal-overlay.active { display: flex; }

            .login-modal-content {
                position: relative;
                width: min(360px, calc(100% - 1.5rem));
                background: rgba(220, 210, 160, 0.82);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
                border-radius: 18px;
                padding: 1.75rem 1.75rem 1.5rem;
                box-shadow: 0 20px 60px rgba(0,0,0,0.4);
                border: 1px solid rgba(255,255,255,0.35);
            }

            .login-close-icon {
                position: absolute; top: 0.85rem; right: 0.85rem;
                width: 28px; height: 28px; border-radius: 50%;
                border: none; background: rgba(0,0,0,0.12);
                cursor: pointer; font-size: 1.1rem; font-weight: 700;
                display: grid; place-items: center;
                color: #333; transition: background 200ms;
            }
            .login-close-icon:hover { background: rgba(0,0,0,0.22); }

            .lm-title {
                text-align: center;
                font-family: "Fraunces", serif;
                font-size: 1.6rem; font-weight: 700;
                margin: 0 0 1.1rem; color: #1a1a1a;
            }

            .lm-group { margin-bottom: 0.75rem; }

            .lm-label {
                display: block; font-size: 0.8rem;
                font-weight: 600; color: #222;
                margin-bottom: 0.3rem;
            }

            .lm-input {
                width: 100%; padding: 0.65rem 0.9rem;
                border-radius: 8px;
                border: 1.5px solid rgba(43,143,144,0.2);
                background: rgba(200,210,170,0.55);
                font: inherit; font-size: 0.9rem; color: #1a1a1a;
                transition: border-color 200ms, background 200ms;
            }
            .lm-input:focus {
                outline: none;
                border-color: #2b8f90;
                background: rgba(220,230,185,0.7);
            }
            .lm-input::placeholder { color: rgba(30,30,30,0.45); }

            .lm-agree-row {
                display: flex; align-items: flex-start; gap: 0.5rem;
                margin: 0.6rem 0 0.9rem;
            }

            .lm-submit-btn {
                width: 100%; padding: 0.75rem;
                background: rgba(180,200,150,0.75);
                border: 1.5px solid rgba(100,140,80,0.4);
                border-radius: 8px;
                font: inherit; font-size: 0.95rem; font-weight: 700;
                color: #1a1a1a; cursor: pointer;
                transition: background 200ms, transform 180ms;
            }
            .lm-submit-btn:hover { background: rgba(160,190,120,0.85); transform: translateY(-1px); }

            .lm-or-row {
                display: grid; grid-template-columns: 1fr auto 1fr;
                align-items: center; gap: 0.6rem;
                margin: 0.85rem 0;
            }
            .lm-or-row span:first-child,
            .lm-or-row span:last-child {
                height: 1px; background: rgba(0,0,0,0.2);
            }
            .lm-or-text { font-size: 0.82rem; color: #444; font-weight: 600; }

            .lm-google-btn {
                width: 100%; padding: 0.7rem;
                background: rgba(200,210,165,0.7);
                border: 1.5px solid rgba(100,130,80,0.35);
                border-radius: 8px;
                font: inherit; font-size: 0.9rem; font-weight: 600;
                color: #1a1a1a; cursor: pointer;
                display: flex; align-items: center; justify-content: center; gap: 0.6rem;
                transition: background 200ms;
            }
            .lm-google-btn:hover { background: rgba(185,200,145,0.85); }

            .login-feedback {
                border-radius: 8px; padding: 0.65rem 0.85rem;
                font-size: 0.85rem; margin-bottom: 0.75rem;
            }
            .login-feedback.error { background: rgba(220,80,80,0.15); color: #7d2b2b; border: 1px solid rgba(220,80,80,0.25); }
            .login-feedback.success { background: rgba(80,180,100,0.15); color: #1f6b38; border: 1px solid rgba(80,180,100,0.25); }
            .login-security-note { background: rgba(43,143,144,0.1); border-radius: 8px; padding: 0.65rem 0.85rem; color: #2b4a4a; border: 1px solid rgba(43,143,144,0.2); }
            .login-remember { display:flex; align-items:center; gap:0.5rem; margin-top:0.35rem; font-size:0.85rem; }
            .login-signup-link { text-align:center; font-size:0.82rem; color:#555; margin-top:0.6rem; }
        </style>

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
        <!-- TERMS & PRIVACY MODAL -->
        <div class="modal-overlay" id="termsModal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="termsModalTitle" tabindex="-1" style="max-width:720px; display:flex; flex-direction:column; padding:0; overflow:hidden;">
                <button class="close-icon" onclick="closeTermsModal()" aria-label="Close">×</button>
                <div class="modal-header" style="padding:1.5rem 2rem; border-bottom:1px solid #eee;">
                    <h2 id="termsModalTitle" style="margin:0; font-size:1.35rem;">Terms &amp; Privacy</h2>
                    <p style="margin:0.35rem 0 0; font-size:0.88rem; color:#666;">BicolVax Online Appointment and Vaccination Scheduling System</p>
                </div>
                <div style="display:flex; gap:0.5rem; padding:1rem 2rem; border-bottom:1px solid #eee;">
                    <button type="button" id="termsTabBtn" class="modal-button" onclick="showTermsTab('terms')" style="padding:0.45rem 1.1rem; font-size:0.82rem;">Terms and Conditions</button>
                    <button type="button" id="privacyTabBtn" class="modal-button close" onclick="showTermsTab('privacy')" style="padding:0.45rem 1.1rem; font-size:0.82rem;">Privacy Policy</button>
                </div>
                <div style="overflow-y:auto; padding:1.5rem 2rem; flex:1; font-size:0.88rem; line-height:1.65; color:#222;">
                    <div id="termsTab">
                        <h3 style="margin:0 0 0.75rem; color:#2b8f90;">TERMS AND CONDITIONS</h3>
                        <p>This appointment and vaccination scheduling system operates on a first-come, first-served basis, subject to schedule availability and clinic approval. Users are responsible for providing accurate and complete information. Incorrect, incomplete, or misleading information may result in the cancellation or rejection of the appointment request. Patient information collected through the system will be used solely for appointment scheduling, vaccination monitoring, and clinic record management purposes. BicolVax Clinic reserves the right to approve, reschedule, or decline appointment requests based on schedule availability and operational requirements.</p>
                        <p>The clinic shall not be held responsible for missed notifications caused by incorrect email addresses, internet connectivity issues, or other technical problems beyond its control. By proceeding with this application, I acknowledge that I have read and understood the Terms and Conditions of the BicolVax Online Appointment and Vaccination Scheduling System. I consent to the collection, processing, and use of my information for clinic-related services in accordance with applicable data privacy regulations.</p>
                    </div>
                    <div id="privacyTab" style="display:none;">
                        <h3 style="margin:0 0 0.75rem; color:#2b8f90;">PRIVACY POLICY</h3>
                        <p>BicolVax Clinic is committed to protecting your personal information and maintaining your privacy.<br>
                        The information you provide through this system, including your name, contact details, appointment information, and vaccination records, will be collected and used solely for appointment scheduling, vaccination monitoring, patient record management, and other clinic-related services.<br>
                        Your personal information will be kept confidential and will only be accessed by authorized clinic personnel when necessary to provide healthcare services.</p>
                        <p>BicolVax Clinic will take reasonable measures to protect your information from unauthorized access, disclosure, alteration, or misuse. The clinic will not sell, share, or disclose your personal information to third parties unless required by law or authorized by the patient. By using this system, you consent to the collection, processing, and storage of your personal information for legitimate healthcare and administrative purposes in accordance with the Data Privacy Act of 2012.</p>
                    </div>
                </div>
                <div style="padding:1rem 2rem; border-top:1px solid #eee; text-align:right;">
                    <button type="button" class="modal-button close" onclick="closeTermsModal()">Close</button>
                </div>
            </div>
        </div>

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

                            <div class="agree-row" style="margin:0.25rem 0 0; padding:0;">
                                <input type="checkbox" id="registerAgree" required style="width:16px;height:16px;accent-color:#2b8f90;cursor:pointer;">
                                <label for="registerAgree" style="cursor:pointer; font-size:0.82rem; color:#333; line-height:1.5;">
                                    I have read and agree to the
                                    <a href="#" onclick="openTermsModal('terms'); return false;" style="color:#2b8f90; text-decoration:underline; font-weight:600;">Terms and Conditions</a>
                                    and
                                    <a href="#" onclick="openTermsModal('privacy'); return false;" style="color:#2b8f90; text-decoration:underline; font-weight:600;">Privacy Policy</a>.
                                </label>
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

            function openTermsModal(tab) {
                const modal = document.getElementById('termsModal');
                if (!modal) return;
                showTermsTab(tab || 'terms');
                modal.classList.add('active');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                const title = document.getElementById('termsModalTitle');
                if (title) title.textContent = (tab === 'privacy') ? 'Privacy Policy' : 'Terms and Conditions';
            }

            function closeTermsModal() {
                const modal = document.getElementById('termsModal');
                if (!modal) return;
                modal.classList.remove('active');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = 'auto';
            }

            function showTermsTab(tab) {
                const isTerms = tab === 'terms';
                document.getElementById('termsTab').style.display = isTerms ? '' : 'none';
                document.getElementById('privacyTab').style.display = isTerms ? 'none' : '';
                const termsBtn = document.getElementById('termsTabBtn');
                const privacyBtn = document.getElementById('privacyTabBtn');
                if (termsBtn && privacyBtn) {
                    termsBtn.classList.toggle('close', !isTerms);
                    privacyBtn.classList.toggle('close', isTerms);
                }
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
                    closeTermsModal();
                }
            });

            // Close terms modal when clicking outside the content
            document.getElementById('termsModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeTermsModal();
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
 