@extends('layouts.superadmin')

@section('title', 'Reports')

@section('content')
<div class="page-header">
    <h1>Reports</h1>
    <p>Aggregated and per-branch patient statistics.</p>
</div>

{{-- Branch filter --}}
<div style="display:flex; gap:0.5rem; margin-bottom:1.5rem; flex-wrap:wrap; align-items:center;">
    <span style="font-size:0.85rem; color:#666; font-weight:600;">Filter by branch:</span>
    <button class="btn btn-secondary" style="padding:0.4rem 0.9rem; font-size:0.82rem;" onclick="filterBranch('all', this)">All Branches</button>
    @foreach($branches as $b)
        <button class="btn btn-outline" style="padding:0.4rem 0.9rem; font-size:0.82rem;" onclick="filterBranch({{ $b->id }}, this)">{{ $b->name }}</button>
    @endforeach
</div>

{{-- Per-branch summary cards --}}
<div id="branchCards" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
    @foreach($branchReports as $report)
        <div class="content-card branch-card" data-branch="{{ $report['id'] }}" style="padding:1.25rem;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <div>
                    <div style="font-weight:700; color:#1a3a3a;">{{ $report['name'] }}</div>
                    <div style="font-size:0.78rem; color:#666;">{{ $report['location'] }}</div>
                </div>
                <a href="{{ route('superadmin.branches.view', $report['id']) }}" class="btn btn-secondary" style="padding:0.3rem 0.7rem; font-size:0.78rem;">View →</a>
            </div>
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem;">
                <div style="background:#f6fbfb; border-radius:6px; padding:0.6rem; text-align:center;">
                    <div style="font-size:1.3rem; font-weight:700; color:#2b8f90;">{{ $report['total'] }}</div>
                    <div style="font-size:0.7rem; color:#666;">Total</div>
                </div>
                <div style="background:#f0faf4; border-radius:6px; padding:0.6rem; text-align:center;">
                    <div style="font-size:1.3rem; font-weight:700; color:#50c878;">{{ $report['approved'] }}</div>
                    <div style="font-size:0.7rem; color:#666;">Approved</div>
                </div>
                <div style="background:#fffbf0; border-radius:6px; padding:0.6rem; text-align:center;">
                    <div style="font-size:1.3rem; font-weight:700; color:#ff9800;">{{ $report['pending'] }}</div>
                    <div style="font-size:0.7rem; color:#666;">Pending</div>
                </div>
                <div style="background:#fff5f5; border-radius:6px; padding:0.6rem; text-align:center;">
                    <div style="font-size:1.1rem; font-weight:700; color:#ef4444;">{{ $report['severe'] }}</div>
                    <div style="font-size:0.7rem; color:#666;">Severe</div>
                </div>
                <div style="background:#f5f0ff; border-radius:6px; padding:0.6rem; text-align:center;">
                    <div style="font-size:1.1rem; font-weight:700; color:#8b5cf6;">{{ $report['cat_ii'] }}</div>
                    <div style="font-size:0.7rem; color:#666;">Cat II</div>
                </div>
                <div style="background:#fff5ee; border-radius:6px; padding:0.6rem; text-align:center;">
                    <div style="font-size:1.1rem; font-weight:700; color:#f97316;">{{ $report['cat_iii'] }}</div>
                    <div style="font-size:0.7rem; color:#666;">Cat III</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- All Patients Table --}}
<div class="content-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <h2 style="color:#2b8f90; font-size:1.2rem; font-weight:700;">All Patients</h2>
        <input type="text" id="reportSearch" placeholder="Search patients…"
               style="width:220px; padding:0.5rem 0.8rem; border:1px solid #ddd; border-radius:4px; font-size:0.85rem;"
               oninput="searchPatients()">
    </div>
    <div style="overflow-x:auto;">
        <table id="patientsTable">
            <thead>
                <tr>
                    <th>Branch</th>
                    <th>Case No.</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Category</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allPatients as $p)
                    <tr data-branch="{{ $p->branch_id ?? '' }}">
                        <td style="color:#2b8f90; font-size:0.85rem; font-weight:600;">{{ $p->branch?->name ?? '—' }}</td>
                        <td>{{ $p->case_no }}</td>
                        <td style="font-weight:600;">{{ $p->full_name }}</td>
                        <td>{{ $p->age }}</td>
                        <td>{{ strtoupper(str_replace('category_', 'CAT ', $p->cat_category ?? '')) }}</td>
                        <td>{{ ucfirst($p->severity ?? '—') }}</td>
                        <td>
                            <span class="badge {{ $p->status === 'approved' ? 'badge-approved' : 'badge-pending' }}">
                                {{ $p->status === 'approved' ? 'Approved' : 'Pending' }}
                            </span>
                        </td>
                        <td style="color:#666; font-size:0.85rem;">{{ $p->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    let activeBranch = 'all';

    function filterBranch(id, btn) {
        activeBranch = id;

        document.querySelectorAll('[onclick^="filterBranch"]').forEach(b => {
            b.classList.remove('btn-secondary');
            b.classList.add('btn-outline');
        });
        btn.classList.add('btn-secondary');
        btn.classList.remove('btn-outline');

        document.querySelectorAll('.branch-card').forEach(card => {
            card.style.display = (id === 'all' || card.dataset.branch == id) ? '' : 'none';
        });

        document.querySelectorAll('#patientsTable tbody tr').forEach(row => {
            row.style.display = (id === 'all' || row.dataset.branch == id) ? '' : 'none';
        });
    }

    function searchPatients() {
        const q = document.getElementById('reportSearch').value.toLowerCase();
        document.querySelectorAll('#patientsTable tbody tr').forEach(row => {
            const branchVisible = activeBranch === 'all' || row.dataset.branch == activeBranch;
            const textMatch = row.textContent.toLowerCase().includes(q);
            row.style.display = (branchVisible && textMatch) ? '' : 'none';
        });
    }
</script>
@endsection
