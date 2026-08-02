@extends('layouts.superadmin')

@section('title', 'Branches')

@section('content')
<div class="page-header">
    <h1>Branches</h1>
    <p>Manage the 8 BicolVax clinic branches.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
    <button class="btn btn-primary" onclick="openModal('createBranchModal')">+ New Branch</button>
</div>

<div class="content-card">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Branch Name</th>
                    <th>Location</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Patients</th>
                    <th>Appointments</th>
                    <th>Admins</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branches as $branch)
                    <tr>
                        <td style="font-weight:600;">{{ $branch->name }}</td>
                        <td>{{ $branch->location }}</td>
                        <td style="color:#666;">{{ $branch->address ?? '—' }}</td>
                        <td>{{ $branch->contact ?? '—' }}</td>
                        <td><strong style="color:#2b8f90;">{{ $branch->patients_count }}</strong></td>
                        <td><strong style="color:#3b82f6;">{{ $branch->appointments_count }}</strong></td>
                        <td>{{ $branch->admins_count }}</td>
                        <td>
                            <span class="badge {{ $branch->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $branch->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; gap:0.4rem; flex-wrap:wrap;">
                                <a href="{{ route('superadmin.branches.view', $branch) }}" class="btn btn-secondary" style="padding:0.35rem 0.7rem; font-size:0.8rem;">View</a>
                                <button class="btn btn-warning" style="padding:0.35rem 0.7rem; font-size:0.8rem;"
                                    onclick='openEditBranch(@json($branch))'>Edit</button>
                                <form action="{{ route('superadmin.branches.destroy', $branch) }}" method="POST"
                                      data-confirm="true" data-confirm-message="Delete branch {{ $branch->name }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger" style="padding:0.35rem 0.7rem; font-size:0.8rem;" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center; color:#999; padding:2rem;">No branches yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Create Branch Modal --}}
<div class="modal-overlay" id="createBranchModal">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2 style="margin:0;">Add New Branch</h2>
            <button onclick="closeModal('createBranchModal')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#666;">✕</button>
        </div>
        <form action="{{ route('superadmin.branches.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Branch Name *</label>
                    <input type="text" name="name" placeholder="e.g. Naga City Branch" required>
                </div>
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" placeholder="e.g. Naga City, Camarines Sur" required>
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" placeholder="Full address">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact" placeholder="09XXXXXXXXX">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="branch@bicolvax.com">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('createBranchModal')">Cancel</button>
                <button type="submit" class="btn-submit">Create Branch</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Branch Modal --}}
<div class="modal-overlay" id="editBranchModal">
    <div class="modal-content">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h2 style="margin:0;">Edit Branch</h2>
            <button onclick="closeModal('editBranchModal')" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#666;">✕</button>
        </div>
        <form id="editBranchForm" method="POST">
            @csrf @method('PATCH')
            <div class="form-row">
                <div class="form-group">
                    <label>Branch Name *</label>
                    <input type="text" name="name" id="editName" required>
                </div>
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" id="editLocation" required>
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" id="editAddress">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact" id="editContact">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="editEmail">
                </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="is_active" id="editIsActive">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editBranchModal')">Cancel</button>
                <button type="submit" class="btn-submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditBranch(branch) {
        document.getElementById('editBranchForm').action = '/superadmin/branches/' + branch.id;
        document.getElementById('editName').value     = branch.name;
        document.getElementById('editLocation').value = branch.location;
        document.getElementById('editAddress').value  = branch.address  || '';
        document.getElementById('editContact').value  = branch.contact  || '';
        document.getElementById('editEmail').value    = branch.email    || '';
        document.getElementById('editIsActive').value = branch.is_active ? '1' : '0';
        openModal('editBranchModal');
    }
</script>
@endsection
