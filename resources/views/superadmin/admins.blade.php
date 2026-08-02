@extends('layouts.superadmin')

@section('title', 'Branch Admins')

@section('content')
<div class="page-header">
    <h1>Branch Admins</h1>
    <p>Create and assign admin accounts to branches.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
    <button class="btn btn-primary" onclick="openModal('createAdminModal')">+ New Branch Admin</button>
</div>

<div class="content-card">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Branch</th>
                    <th>Location</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td style="font-weight:600;">{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            @if($admin->branch)
                                <span style="color:#2b8f90; font-weight:600;">{{ $admin->branch->name }}</span>
                            @else
                                <span style="color:#999;">No branch assigned</span>
                            @endif
                        </td>
                        <td style="color:#666;">{{ $admin->branch?->location ?? '—' }}</td>
                        <td style="color:#666;">{{ $admin->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display:flex; gap:0.4rem;">
                                <button class="btn btn-warning" style="padding:0.35rem 0.7rem; font-size:0.8rem;"
                                    onclick='openEditAdmin(@json($admin))'>Edit</button>
                                <form action="{{ route('superadmin.admins.destroy', $admin) }}" method="POST"
                                      data-confirm="true" data-confirm-message="Delete admin {{ $admin->name }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger" style="padding:0.35rem 0.7rem; font-size:0.8rem;" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#999; padding:2rem;">No branch admins yet. Create one to get started.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Create Admin Modal --}}
<div class="modal-overlay" id="createAdminModal">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2 style="margin:0;">Create Branch Admin</h2>
            <button onclick="closeModal('createAdminModal')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#666;">✕</button>
        </div>
        <form action="{{ route('superadmin.admins.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" placeholder="Admin Name" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" placeholder="admin@bicolvax.com" required>
                </div>
            </div>
            <div class="form-group">
                <label>Assign to Branch *</label>
                <select name="branch_id" required>
                    <option value="">— Select Branch —</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }} – {{ $branch->location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" placeholder="Min 8 characters" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('createAdminModal')">Cancel</button>
                <button type="submit" class="btn-submit">Create Admin</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Admin Modal --}}
<div class="modal-overlay" id="editAdminModal">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2 style="margin:0;">Edit Branch Admin</h2>
            <button onclick="closeModal('editAdminModal')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#666;">✕</button>
        </div>
        <form id="editAdminForm" method="POST">
            @csrf @method('PATCH')
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" id="eaName" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" id="eaEmail" required>
                </div>
            </div>
            <div class="form-group">
                <label>Assign to Branch *</label>
                <select name="branch_id" id="eaBranch" required>
                    <option value="">— Select Branch —</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }} – {{ $branch->location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>New Password <span style="color:#999; font-size:0.8rem;">(leave blank to keep)</span></label>
                    <input type="password" name="password" placeholder="Min 8 characters">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" placeholder="Repeat password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editAdminModal')">Cancel</button>
                <button type="submit" class="btn-submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditAdmin(admin) {
        document.getElementById('editAdminForm').action = '/superadmin/admins/' + admin.id;
        document.getElementById('eaName').value   = admin.name;
        document.getElementById('eaEmail').value  = admin.email;
        document.getElementById('eaBranch').value = admin.branch_id || '';
        openModal('editAdminModal');
    }
</script>
@endsection
