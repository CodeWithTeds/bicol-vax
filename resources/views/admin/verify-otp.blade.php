@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <h1>Verify OTP</h1>
        <p>Enter the 6-digit OTP sent to your admin email.</p>
    </div>

    <div class="content-card" style="max-width:480px;">
        <form method="POST" action="{{ route('admin.otp.verify.post') }}">
            @csrf
            <div style="margin-bottom:1rem">
                <label>OTP Code</label>
                <input type="text" name="code" required style="width:100%;padding:0.5rem">
                @error('code')<div style="color:red">{{ $message }}</div>@enderror
            </div>

            <button class="btn btn-primary" type="submit">Verify & Login</button>
        </form>
    </div>
@endsection
