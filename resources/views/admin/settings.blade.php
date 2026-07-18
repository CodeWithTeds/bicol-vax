@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <div class="page-header">
        <h1>Settings</h1>
        <p>Manage clinic and account settings</p>
    </div>

    <div class="content-card">
        <div class="content-header">
            <h2>Account Settings</h2>
        </div>
        <div style="padding: 2rem;">
            <p style="margin-bottom: 1rem; color: #666;">Configure your account preferences and clinic settings.</p>
            <form>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Clinic Name</label>
                    <input type="text" placeholder="BicolVax Clinic" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email</label>
                    <input type="email" placeholder="admin@example.com" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Phone</label>
                    <input type="tel" placeholder="+63 9xx xxx xxxx" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
@endsection
