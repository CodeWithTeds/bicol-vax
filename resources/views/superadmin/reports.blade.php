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
                    <div style="font-size:1.1rem; font-weight:700; color:#ef4444;">{{ $report['cancelled'] }}</div>
                    <div style="font-size:0.7rem; color:#666;">Cancelled</div>
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

{{-- Monthly Patient Status Report --}}
<div class="content-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:0.5rem;">
        <h2 style="color:#2b8f90; font-size:1.2rem; font-weight:700;">Monthly Patient Status Report</h2>
        <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
            <span style="font-size:0.85rem; color:#666; font-weight:600;">Year:</span>
            <select id="reportYear" onchange="filterMonthlyReport()"
                    style="padding:0.4rem 0.6rem; border:1px solid #ddd; border-radius:4px; font-size:0.85rem;">
                <option value="all">All Years</option>
                @php
                    $reportYears = collect($monthlyReports)->pluck('year')->unique()->sort()->values();
                    $currentYear = now()->format('Y');
                    $currentQuarter = (int) ceil(now()->month / 3);
                @endphp
                @foreach($reportYears as $y)
                    <option value="{{ $y }}" {{ (string) $y === $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <span style="font-size:0.85rem; color:#666; font-weight:600;">Quarter:</span>
            <select id="reportQuarter" onchange="filterMonthlyReport()"
                    style="padding:0.4rem 0.6rem; border:1px solid #ddd; border-radius:4px; font-size:0.85rem;">
                <option value="all">All Quarters</option>
                <option value="1" {{ $currentQuarter === 1 ? 'selected' : '' }}>Q1 (Jan–Mar)</option>
                <option value="2" {{ $currentQuarter === 2 ? 'selected' : '' }}>Q2 (Apr–Jun)</option>
                <option value="3" {{ $currentQuarter === 3 ? 'selected' : '' }}>Q3 (Jul–Sep)</option>
                <option value="4" {{ $currentQuarter === 4 ? 'selected' : '' }}>Q4 (Oct–Dec)</option>
            </select>
        </div>
    </div>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Total Patients</th>
                    <th>Registered</th>
                    <th>Complete</th>
                    <th>Ongoing</th>
                    <th>Cancelled</th>
                    <th>Missing</th>
                </tr>
            </thead>
            <tbody id="monthlyReportBody">
                @forelse($monthlyReports as $r)
                    <tr class="month-row" data-year="{{ $r['year'] }}" data-quarter="{{ $r['quarter'] }}"
                        data-month="{{ $r['key'] }}" data-month-label="{{ $r['label'] }}"
                        onclick="filterByMonth('{{ $r['key'] }}', this)"
                        title="Click to show patients registered in {{ $r['label'] }}"
                        style="cursor:pointer;">
                        <td style="font-weight:600;">{{ $r['label'] }}</td>
                        <td>{{ $r['total'] }}</td>
                        <td>{{ $r['registered'] }}</td>
                        <td>
                            <span class="badge badge-approved" style="display:inline-block; min-width:44px; text-align:center;">{{ $r['complete'] }}</span>
                        </td>
                        <td>
                            <span class="badge badge-pending" style="display:inline-block; min-width:44px; text-align:center;">{{ $r['ongoing'] }}</span>
                        </td>
                        <td>
                            <span class="badge badge-not_approved" style="display:inline-block; min-width:44px; text-align:center;">{{ $r['cancelled'] }}</span>
                        </td>
                        <td>
                            <span class="badge badge-none" style="display:inline-block; min-width:44px; text-align:center;">{{ $r['missing'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:#999; padding:1rem;">No patient data available yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- All Patients Table --}}
<div class="content-card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; gap:0.5rem; flex-wrap:wrap;">
        <h2 style="color:#2b8f90; font-size:1.2rem; font-weight:700;">All Patients</h2>
        <div style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
            <span id="monthFilterLabel" style="font-size:0.82rem; color:#2b8f90; font-weight:600; display:none;"></span>
            <button id="clearMonthFilter" onclick="clearMonthFilter()" class="btn btn-secondary"
                    style="padding:0.4rem 0.9rem; font-size:0.82rem; display:none;">Clear Month Filter</button>
            <input type="text" id="reportSearch" placeholder="Search patients…"
                   style="width:220px; padding:0.5rem 0.8rem; border:1px solid #ddd; border-radius:4px; font-size:0.85rem;"
                   oninput="searchPatients()">
        </div>
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
                    <tr data-branch="{{ $p->branch_id ?? '' }}" data-month="{{ $p->created_at->format('Y-m') }}">
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
    let activeMonth = 'all';

    function applyPatientFilters() {
        const q = document.getElementById('reportSearch').value.toLowerCase();

        document.querySelectorAll('#patientsTable tbody tr').forEach(row => {
            const branchVisible = activeBranch === 'all' || row.dataset.branch == activeBranch;
            const monthVisible = activeMonth === 'all' || row.dataset.month === activeMonth;
            const textMatch = row.textContent.toLowerCase().includes(q);
            row.style.display = (branchVisible && monthVisible && textMatch) ? '' : 'none';
        });
    }

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

        applyPatientFilters();
    }

    function searchPatients() {
        applyPatientFilters();
    }

    function filterByMonth(month, row) {
        if (activeMonth === month) {
            clearMonthFilter();
            return;
        }

        activeMonth = month;
        document.querySelectorAll('#monthlyReportBody tr.month-row').forEach(r => r.classList.remove('selected'));
        row.classList.add('selected');
        syncMonthFilterUi();
        applyPatientFilters();
    }

    function clearMonthFilter() {
        activeMonth = 'all';
        document.querySelectorAll('#monthlyReportBody tr.month-row').forEach(r => r.classList.remove('selected'));
        syncMonthFilterUi();
        applyPatientFilters();
    }

    function syncMonthFilterUi() {
        const label = document.getElementById('monthFilterLabel');
        const clearBtn = document.getElementById('clearMonthFilter');

        if (activeMonth === 'all') {
            label.style.display = 'none';
            clearBtn.style.display = 'none';
        } else {
            const row = document.querySelector(`#monthlyReportBody tr.month-row[data-month="${activeMonth}"]`);
            label.textContent = 'Filtering month: ' + (row ? row.dataset.monthLabel : activeMonth);
            label.style.display = '';
            clearBtn.style.display = '';
        }
    }

    function filterMonthlyReport() {
        const year = document.getElementById('reportYear').value;
        const quarter = document.getElementById('reportQuarter').value;

        document.querySelectorAll('#monthlyReportBody tr.month-row').forEach(row => {
            const yearMatch = year === 'all' || row.dataset.year === year;
            const quarterMatch = quarter === 'all' || row.dataset.quarter === quarter;
            row.style.display = (yearMatch && quarterMatch) ? '' : 'none';
        });
    }
</script>
<style>
    #monthlyReportBody tr.month-row:hover { background: #eaf7f7; }
    #monthlyReportBody tr.month-row.selected { background: #d5efef; outline: 1px solid #2b8f90; }
</style>
@endsection
