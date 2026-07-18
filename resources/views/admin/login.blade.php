@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1>Admin Login</h1>
        <p>Sign in to your admin account to continue.</p>
    </div>

    <div class="content-card" style="max-width:480px;">
        @if(session('status'))
            <div style="margin-bottom:1rem;color:green">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div style="margin-bottom:1rem">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;padding:0.5rem">
                @error('email')<div style="color:red">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:1rem">
                <label>Password</label>
                <div style="position:relative;">
                    <input type="password" id="admin_login_password" name="password" required style="width:100%;padding:0.5rem;padding-right:44px;">
                    <button type="button" id="toggleAdminLoginPassword" aria-label="Show password" style="position:absolute; right:8px; top:50%; transform:translateY(-50%); border:none; background:transparent; cursor:pointer; font-weight:700; color:#374151;"></button>
                </div>
                @error('password')<div style="color:red">{{ $message }}</div>@enderror
            </div>

            <button class="btn btn-primary" type="submit">Login</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    (function(){
        const pwd = document.getElementById('admin_login_password');
        const btn = document.getElementById('toggleAdminLoginPassword');
        if (!pwd || !btn) return;
        const eyeSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        const eyeOffSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.67 21.67 0 0 1 5.06-5.94"></path><path d="M1 1l22 22"></path><path d="M9.88 9.88A3 3 0 0 0 14.12 14.12"></path></svg>';
        function setIcon(shown){ btn.innerHTML = shown ? eyeOffSvg : eyeSvg; btn.setAttribute('aria-pressed', shown ? 'true' : 'false'); }
        setIcon(false);
        btn.addEventListener('click', function(){ const showing = pwd.type==='text'; pwd.type = showing ? 'password' : 'text'; setIcon(!showing); });
    })();
</script>
@endpush
