@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
    <style>
        .page-background {
            background: transparent;
            padding: 0;
            border-radius: 0;
            margin: 0 0 2rem 0;
        }

        .reports-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .reports-title {
            flex: 1;
        }

        .reports-title h1 {
            font-size: 2rem;
            color: #333;
            margin: 0;
            font-weight: 700;
        }

        .reports-title p {
            display: none;
        }

        .btn-refresh {
            background: linear-gradient(135deg, #2b8f90 0%, #1f6566 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .btn-refresh:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 208, 132, 0.6);
        }

        .filters-section {
            background: white;
            padding: 2rem 1rem;
            border-radius: 0;
            margin: 0 -2rem 2rem -2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: flex-end;
            box-shadow: none;
            border: none;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            flex: 0 0 auto;
            min-width: 180px;
        }

        .filter-group label {
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #333;
            font-size: 0.95rem;
        }

        .filter-group select,
        .filter-group input {
            padding: 0.9rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .date-range-group {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex: 0 0 auto;
        }

        .date-range-group input {
            padding: 0.9rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            flex: 0 0 auto;
            width: 140px;
        }

        .date-range-group span {
            text-align: center;
            font-weight: 600;
            color: #333;
        }

        .btn-generate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.9rem 2rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            flex: 0 0 auto;
            margin-top: 1.5rem;
        }

        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin: 0 -2rem 2rem -2rem;
            padding: 0 1rem;
        }

        .chart-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .chart-card h3 {
            margin: 0 0 1.5rem 0;
            color: #333;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 320px;
            margin-bottom: 2rem;
            padding: 2rem 1rem 3rem 1rem;
            background: linear-gradient(180deg, rgba(102, 126, 234, 0.05) 0%, rgba(102, 126, 234, 0) 100%);
            border-radius: 12px;
            position: relative;
        }

        .bar-chart::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
        }

        .bar {
            width: 40px;
            border-radius: 12px 12px 0 0;
            position: relative;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
            animation: slideUp 0.6s ease-out both;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .bar:nth-child(1) { background: linear-gradient(180deg, #667eea 0%, #764ba2 100%); animation-delay: 0.05s; }
        .bar:nth-child(2) { background: linear-gradient(180deg, #f093fb 0%, #f5576c 100%); animation-delay: 0.1s; }
        .bar:nth-child(3) { background: linear-gradient(180deg, #4facfe 0%, #00f2fe 100%); animation-delay: 0.15s; }
        .bar:nth-child(4) { background: linear-gradient(180deg, #43e97b 0%, #38f9d7 100%); animation-delay: 0.2s; }
        .bar:nth-child(5) { background: linear-gradient(180deg, #fa709a 0%, #fee140 100%); animation-delay: 0.25s; }
        .bar:nth-child(6) { background: linear-gradient(180deg, #30cfd0 0%, #330867 100%); animation-delay: 0.3s; }
        .bar:nth-child(7) { background: linear-gradient(180deg, #a8edea 0%, #fed6e3 100%); animation-delay: 0.35s; }

        @keyframes slideUp {
            from {
                height: 0;
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bar:hover {
            transform: translateY(-10px) scale(1.1);
            filter: drop-shadow(0 12px 30px rgba(102, 126, 234, 0.6)) brightness(1.1);
        }

        .bar-label {
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.9rem;
            color: #666;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .bar:hover .bar-label {
            color: #333;
            font-weight: 800;
        }

        .bar-value {
            position: absolute;
            top: -40px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1rem;
            color: white;
            font-weight: 800;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            opacity: 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .bar:hover .bar-value {
            opacity: 1;
            transform: translateX(-50%) translateY(-5px);
        }

        .pie-chart-container {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 250px;
            margin-bottom: 1.5rem;
        }

        .pie-chart {
            width: 200px;
            height: 200px;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1));
        }

        .pie-legend {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            padding: 0.5rem 0;
        }

        .legend-color {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 0 -2rem 2rem -2rem;
            padding: 2rem 1rem;
        }

        .stat-box {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            padding: 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            border-left: 4px solid #667eea;
        }

        .stat-box:nth-child(1) {
            border-left-color: #4caf50;
        }

        .stat-box:nth-child(2) {
            border-left-color: #42a5f5;
        }

        .stat-box:nth-child(3) {
            border-left-color: #ffa726;
        }

        .stat-box:nth-child(4) {
            border-left-color: #ef5350;
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-box:nth-child(1) .stat-icon {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
        }

        .stat-box:nth-child(2) .stat-icon {
            background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%);
        }

        .stat-box:nth-child(3) .stat-icon {
            background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
        }

        .stat-box:nth-child(4) .stat-icon {
            background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
        }

        .stat-content h4 {
            margin: 0 0 0.25rem 0;
            font-size: 2rem;
            color: #333;
            font-weight: 800;
        }

        .stat-content p {
            margin: 0;
            font-size: 0.95rem;
            color: #666;
            font-weight: 600;
        }

        .export-section {
            background: white;
            padding: 2rem 1rem;
            border-radius: 0;
            margin: 0 -2rem 0 -2rem;
            box-shadow: none;
            border: none;
        }

        /* Report table styles */
        .report-table-card {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            margin: 0 -2rem 2rem -2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            overflow: auto;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        table.report-table th,
        table.report-table td {
            padding: 0.9rem 0.8rem;
            text-align: left;
            border-bottom: 1px solid #f1f3f5;
            font-size: 0.95rem;
            color: #2b2b2b;
        }

        table.report-table thead th {
            background: #fafcff;
            font-weight: 700;
            color: #334155;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .small-muted {
            color: #6b7280;
            font-size: 0.85rem;
        }

        .export-section h3 {
            margin: 0 0 1.5rem 0;
            color: #333;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .export-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1.5rem;
        }

        .export-btn {
            padding: 1rem;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: white;
        }

        .export-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-pdf {
            background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
        }

        .btn-excel {
            background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);
        }

        .btn-print {
            background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);
        }

        .btn-download {
            background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 100%);
        }

        @media (max-width: 768px) {
            .charts-container {
                grid-template-columns: 1fr;
            }

            .reports-title h1 {
                font-size: 1.8rem;
            }
        }
    </style>

    <div class="page-background">
        <div class="reports-header">
            <div class="reports-title">
                <h1>📊 Analytics Report</h1>
                <p>Real-time insights and performance metrics</p>
            </div>
            <button class="btn-refresh">🔄 Refresh Data</button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <div class="filter-group">
            <label>📋 Report type:</label>
            <select>
                <option>Monthly report</option>
                <option>Weekly report</option>
                <option>Daily report</option>
            </select>
        </div>

        <div class="filter-group">
            <label>🏢 Branch</label>
            <select>
                <option>Branch</option>
                <option>Main Branch</option>
                <option>Sub Branch</option>
            </select>
        </div>

        <div class="filter-group">
            <label>📅 Year</label>
            <select>
                <option value="">All Years</option>
                <option>2023</option>
                <option>2024</option>
                <option>2025</option>
                <option selected>2026</option>
            </select>
        </div>

        <div class="filter-group">
            <label>📆 Date Range</label>
            <div class="date-range-group">
                <input type="date" id="startDate">
                <span>to</span>
                <input type="date" id="endDate">
            </div>
        </div>

        <button class="btn-generate">✨ Generate</button>
    </div>

    <!-- Charts Section -->
    <div class="charts-container">
        <!-- Bar Chart -->
        <div class="chart-card">
            <h3>📈 Monthly Appointments</h3>
            <div class="bar-chart">
                <div style="flex: 1; text-align: center; position: relative; margin: 0 8px; display: flex; align-items: flex-end; justify-content: center; height: 100%;">
                    <div class="bar" style="height: 0%; margin: 0 auto;"><span class="bar-value"></span></div>
                    <span class="bar-label">Jan</span>
                </div>
                <div style="flex: 1; text-align: center; position: relative; margin: 0 8px; display: flex; align-items: flex-end; justify-content: center; height: 100%;">
                    <div class="bar" style="height: 0%; margin: 0 auto;"><span class="bar-value"></span></div>
                    <span class="bar-label">Feb</span>
                </div>
                <div style="flex: 1; text-align: center; position: relative; margin: 0 8px; display: flex; align-items: flex-end; justify-content: center; height: 100%;">
                    <div class="bar" style="height: 0%; margin: 0 auto;"><span class="bar-value"></span></div>
                    <span class="bar-label">Mar</span>
                </div>
                <div style="flex: 1; text-align: center; position: relative; margin: 0 8px; display: flex; align-items: flex-end; justify-content: center; height: 100%;">
                    <div class="bar" style="height: 0%; margin: 0 auto;"><span class="bar-value"></span></div>
                    <span class="bar-label">Apr</span>
                </div>
                <div style="flex: 1; text-align: center; position: relative; margin: 0 8px; display: flex; align-items: flex-end; justify-content: center; height: 100%;">
                    <div class="bar" style="height: 0%; margin: 0 auto;"><span class="bar-value"></span></div>
                    <span class="bar-label">May</span>
                </div>
                <div style="flex: 1; text-align: center; position: relative; margin: 0 8px; display: flex; align-items: flex-end; justify-content: center; height: 100%;">
                    <div class="bar" style="height: 0%; margin: 0 auto;"><span class="bar-value"></span></div>
                    <span class="bar-label">Jun</span>
                </div>
                <div style="flex: 1; text-align: center; position: relative; margin: 0 8px; display: flex; align-items: flex-end; justify-content: center; height: 100%;">
                    <div class="bar" style="height: 0%; margin: 0 auto;"><span class="bar-value"></span></div>
                    <span class="bar-label">Jul</span>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="chart-card">
            <h3>📍 Appointment Status</h3>
            <div class="pie-chart-container">
                <div style="text-align: center; color: #999; padding: 2rem;">No data available</div>
            </div>
            <div class="pie-legend">
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #66bb6a 0%, #43a047 100%);"></div>
                    <span><strong>Completed</strong> - 0%</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);"></div>
                    <span><strong>Pending</strong> - 0%</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);"></div>
                    <span><strong>Cancelled</strong> - 0%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <h4>0</h4>
                <p>Total Appointment</p>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">✓</div>
            <div class="stat-content">
                <h4>0</h4>
                <p>Completed</p>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">⏱</div>
            <div class="stat-content">
                <h4>0</h4>
                <p>Pending</p>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">✕</div>
            <div class="stat-content">
                <h4>0</h4>
                <p>Cancelled</p>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="report-table-card">
        <h3 style="margin:0 0 1rem 0;">🗂️ Report Records</h3>
        <div style="overflow:auto;">
            <table class="report-table" id="reportsTable">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th class="small-muted">Email</th>
                        <th class="small-muted">Contact</th>
                        <th class="small-muted">Birthday</th>
                        <th class="small-muted">Age</th>
                        <th class="small-muted">Status</th>
                        <th class="small-muted">Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="7" style="text-align:center; color:#999; padding:1rem;">No records to display</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Section -->
    <div class="export-section">
        <h3>📤 Export Report</h3>
        <div class="export-buttons">
            <button class="export-btn btn-pdf">📄 Export PDF</button>
            <button class="export-btn btn-excel">📊 Export Excel</button>
            <button class="export-btn btn-print">🖨 Print Report</button>
            <button class="export-btn btn-download">⬇ Download</button>
        </div>
    </div>

    <script>
        let reportsData = {
            patients: @json($patientsPayload ?? []),
            currentFilter: 'monthly',
            lastFilteredData: []  // Store last filtered data for exports
        };

        // Initialize reports on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date range (start: Jan 1 this year, end: today)
            const today = new Date();
            const startOfYear = new Date(today.getFullYear(), 0, 1); // January 1 of current year

            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };
            
            const startDateStr = formatDate(startOfYear);
            const endDateStr = formatDate(today);
            
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');

            startDateInput.value = startDateStr;
            endDateInput.value = endDateStr;

            console.log('=== Reports Page Initialized ===');
            console.log('Date inputs set to:', { start: startDateStr, end: endDateStr });
            console.log('Total patients from backend:', reportsData.patients.length);

            if (reportsData.patients.length > 0) {
                console.log('Sample patient:', reportsData.patients[0]);
                console.log('All patients:');
                reportsData.patients.forEach((apt, index) => {
                    console.log(`  ${index + 1}. ${apt.patient} - Registered: ${apt.registered}`);
                });
            }
            
            generateReport();
        });

        function parseAptDate(value) {
            if (!value) return null;
            // Backend sends 'Y-m-d H:i:s' — normalize to ISO before parsing for cross-browser safety
            const normalized = String(value).replace(' ', 'T');
            const date = new Date(normalized);
            return isNaN(date.getTime()) ? null : date;
        }

        function generateReport() {
            const reportType = document.querySelector('.filter-group select').value;
            const branch = document.querySelectorAll('.filter-group select')[1].value;
            const year = parseInt(document.querySelectorAll('.filter-group select')[2].value);
            const startDateInput = document.getElementById('startDate').value;
            const endDateInput = document.getElementById('endDate').value;

            console.log('=== Generate Report ===');
            console.log('Total patients from backend:', reportsData.patients.length);
            if (reportsData.patients.length > 0) {
                console.log('Sample patient:', reportsData.patients[0]);
            }
            console.log('Filter inputs:', { reportType, branch, year, startDateInput, endDateInput });

            // Filter data based on selections
            let filteredData = reportsData.patients;

            console.log('Starting with:', filteredData.length, 'patients');

            // Apply Year filter (only if year is explicitly selected and valid)
            if (year && !isNaN(year)) {
                console.log(`Applying year filter: ${year}`);
                filteredData = filteredData.filter(apt => {
                    const aptDate = parseAptDate(apt.created_at);
                    if (!aptDate) return false;
                    const aptYear = aptDate.getFullYear();
                    console.log(`  Appointment date: ${apt.created_at}, year: ${aptYear}, matches: ${aptYear === year}`);
                    return aptYear === year;
                });
                console.log(`After year filter (${year}):`, filteredData.length, 'appointments');
            } else {
                console.log('Year filter skipped (All Years selected)');
            }

            // Apply Date Range filter
            if (startDateInput && endDateInput) {
                console.log(`Applying date range filter: ${startDateInput} to ${endDateInput}`);
                
                filteredData = filteredData.filter(apt => {
                    // Parse appointment date - handle both formats
                    const aptDate = parseAptDate(apt.created_at);
                    if (!aptDate) {
                        console.log(`  Invalid or missing date: ${apt.created_at}`);
                        return false;
                    }
                    
                    // Parse filter dates - format from input: 'YYYY-MM-DD'
                    const start = new Date(startDateInput + 'T00:00:00');
                    const end = new Date(endDateInput + 'T23:59:59');
                    
                    const isInRange = aptDate >= start && aptDate <= end;
                    console.log(`  Apt: ${apt.created_at} (${aptDate.toISOString()}) | Range: ${start.toISOString()} to ${end.toISOString()} | In range: ${isInRange}`);
                    
                    return isInRange;
                });
                console.log(`After date range filter:`, filteredData.length, 'appointments');
            } else {
                console.warn('Date range filter skipped - start or end date is empty');
            }

            console.log('Final Filtered Data:', filteredData);

            // Store filtered data for exports
            reportsData.lastFilteredData = filteredData;

            // Calculate statistics
            const totalPatients = filteredData.length;
            const completed = filteredData.filter(p => {
                const status = (p.status || '').toLowerCase();
                return status === 'approved' || status === 'completed';
            }).length;
            const pending = filteredData.filter(p => {
                const status = (p.status || '').toLowerCase();
                return status === 'not_approved' || status === 'pending' || status === '' || status === null;
            }).length;
            const cancelled = filteredData.filter(p => {
                const status = (p.status || '').toLowerCase();
                return status === 'cancelled';
            }).length;

            console.log('Statistics - Total patients:', totalPatients, 'Approved:', completed, 'Pending:', pending, 'Cancelled:', cancelled);

            // Update statistics cards
            document.querySelectorAll('.stat-box')[0].querySelector('h4').textContent = totalPatients;
            document.querySelectorAll('.stat-box')[1].querySelector('h4').textContent = completed;
            document.querySelectorAll('.stat-box')[2].querySelector('h4').textContent = pending;
            document.querySelectorAll('.stat-box')[3].querySelector('h4').textContent = cancelled;

            // Update bar chart (monthly)
            updateBarChart(filteredData);

            // Update pie chart
            updatePieChart(completed, pending, cancelled);

                // Render table rows for filtered data
                renderReportTable(filteredData);

            console.log('Report updated successfully!');
        }

        function updateBarChart(data) {
            const monthData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
            
            data.forEach(apt => {
                if (!apt.created_at) return;
                const date = parseAptDate(apt.created_at);
                if (!date) return;
                const month = date.getMonth();
                console.log(`Processing appointment with date: ${apt.created_at}, month index: ${month}`);
                if (month >= 0 && month < 12) {
                    monthData[month]++;
                }
            });

            console.log('All month data:', monthData);
            console.log('Filtered month data (Jan-Jul):', monthData.slice(0, 7));

            // Find max value from months that have data (only Jan-Jul which are first 7)
            const displayMonths = monthData.slice(0, 7);
            const maxValue = Math.max(...displayMonths);
            
            console.log('Max value in displayed months:', maxValue);

            const barChart = document.querySelector('.bar-chart');
            const bars = barChart.querySelectorAll('.bar');
            const barValues = barChart.querySelectorAll('.bar-value');

            console.log('Number of bars:', bars.length);

            // Update all bars
            bars.forEach((bar, index) => {
                // Reset animation
                bar.style.animation = 'none';
                bar.style.height = '0%';
                
                setTimeout(() => {
                    let heightPercent = 0;
                    
                    if (monthData[index] > 0) {
                        // Calculate height based on max value
                        if (maxValue > 0) {
                            heightPercent = (monthData[index] / maxValue) * 100;
                        } else {
                            heightPercent = 0;
                        }
                        // Ensure non-zero values are at least 25% height
                        heightPercent = Math.max(heightPercent, 25);
                    } else {
                        // Zero values get minimal height
                        heightPercent = 3;
                    }

                    console.log(`Bar ${index} (${monthNames[index]}): value=${monthData[index]}, height=${heightPercent}%`);
                    
                    bar.style.height = heightPercent + '%';
                    bar.style.animation = 'slideUp 0.6s ease-out both';
                    bar.style.animationDelay = (index * 0.05) + 's';
                    barValues[index].textContent = monthData[index];
                }, 50);
            });
        }

        function updatePieChart(completed, pending, cancelled) {
            const total = completed + pending + cancelled;
            const container = document.querySelector('.pie-chart-container');
            
            if (total === 0) {
                container.innerHTML = '<div style="text-align: center; color: #999; padding: 2rem;">No data available</div>';
                document.querySelectorAll('.legend-item')[0].querySelector('span').innerHTML = '<strong>Approved</strong> - 0%';
                document.querySelectorAll('.legend-item')[1].querySelector('span').innerHTML = '<strong>Pending</strong> - 0%';
                document.querySelectorAll('.legend-item')[2].querySelector('span').innerHTML = '<strong>Cancelled</strong> - 0%';
                return;
            }

            const completedPercent = ((completed / total) * 100).toFixed(1);
            const pendingPercent = ((pending / total) * 100).toFixed(1);
            const cancelledPercent = ((cancelled / total) * 100).toFixed(1);

            // Create SVG pie chart
            const svg = createPieChart(completed, pending, cancelled, total);
            container.innerHTML = '';
            container.appendChild(svg);

            // Update legend
            document.querySelectorAll('.legend-item')[0].querySelector('span').innerHTML = `<strong>Approved</strong> - ${completedPercent}%`;
            document.querySelectorAll('.legend-item')[1].querySelector('span').innerHTML = `<strong>Pending</strong> - ${pendingPercent}%`;
            document.querySelectorAll('.legend-item')[2].querySelector('span').innerHTML = `<strong>Cancelled</strong> - ${cancelledPercent}%`;
        }

        function renderReportTable(data) {
            const tbody = document.querySelector('#reportsTable tbody');
            tbody.innerHTML = '';
            if (!data || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; color:#999; padding:1rem;">No records to display</td></tr>';
                return;
            }

            // Limit to first 500 rows for performance
            const rows = data.slice(0, 500).map(apt => {
                const registered = apt.registered || apt.created_at || '';
                const birthday = apt.birthday || '';
                const email = apt.email || '-';
                const contact = apt.contact || '-';
                const age = apt.age || '-';
                const status = apt.status || '-';

                return `
                    <tr>
                        <td>${escapeHtml(apt.patient || '-')}</td>
                        <td class="small-muted">${escapeHtml(email)}</td>
                        <td class="small-muted">${escapeHtml(contact)}</td>
                        <td class="small-muted">${escapeHtml(birthday)}</td>
                        <td class="small-muted">${escapeHtml(age)}</td>
                        <td class="small-muted">${escapeHtml(status)}</td>
                        <td class="small-muted">${escapeHtml(registered)}</td>
                    </tr>
                `;
            }).join('');

            tbody.innerHTML = rows;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>"'`]/g, function(match) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '`': '&#96;'
                }[match];
            });
        }

        function createPieChart(completed, pending, cancelled, total) {
            const radius = 100;
            const cx = 100;
            const cy = 100;

            const completedPercent = (completed / total) * 100;
            const pendingPercent = (pending / total) * 100;

            const completedAngle = (completedPercent / 100) * 360;
            const pendingAngle = (pendingPercent / 100) * 360;

            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('viewBox', '0 0 200 200');
            svg.setAttribute('class', 'pie-chart');
            svg.style.width = '200px';
            svg.style.height = '200px';

            const slices = [
                { start: 0, end: completedAngle, color: '#66bb6a' },
                { start: completedAngle, end: completedAngle + pendingAngle, color: '#ffa726' },
                { start: completedAngle + pendingAngle, end: 360, color: '#ef5350' },
            ];

            slices.forEach(({ start, end, color }) => {
                if (end - start <= 0) return;
                svg.appendChild(createPieSlice(cx, cy, radius, start, end, color));
            });

            return svg;
        }

        function createPieSlice(cx, cy, radius, startAngle, endAngle, color) {
            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');

            // A full slice (e.g. one status at 100%) would produce a degenerate
            // arc where start == end, so draw it as a complete circle instead.
            if (endAngle - startAngle >= 359.999) {
                path.setAttribute('d', [
                    `M ${cx} ${cy - radius}`,
                    `A ${radius} ${radius} 0 1 1 ${cx} ${cy + radius}`,
                    `A ${radius} ${radius} 0 1 1 ${cx} ${cy - radius}`,
                    'Z',
                ].join(' '));
            } else {
                const startAngleRad = (startAngle - 90) * Math.PI / 180;
                const endAngleRad = (endAngle - 90) * Math.PI / 180;

                const x1 = cx + radius * Math.cos(startAngleRad);
                const y1 = cy + radius * Math.sin(startAngleRad);
                const x2 = cx + radius * Math.cos(endAngleRad);
                const y2 = cy + radius * Math.sin(endAngleRad);

                const largeArc = endAngle - startAngle > 180 ? 1 : 0;

                path.setAttribute('d', [
                    `M ${cx} ${cy}`,
                    `L ${x1} ${y1}`,
                    `A ${radius} ${radius} 0 ${largeArc} 1 ${x2} ${y2}`,
                    'Z',
                ].join(' '));
            }

            path.setAttribute('fill', color);
            path.setAttribute('stroke', 'white');
            path.setAttribute('stroke-width', '2');
            path.style.filter = 'drop-shadow(0 4px 12px rgba(0, 0, 0, 0.1))';

            return path;
        }

        // Debounce timer for real-time updates
        let updateTimeout;
        let isUpdating = false;

        function scheduleUpdate() {
            // Clear previous timeout
            clearTimeout(updateTimeout);
            
            // Show updating state
            if (!isUpdating) {
                isUpdating = true;
                console.log('Updates scheduled...');
            }

            // Debounce: wait 300ms after last change before updating
            updateTimeout = setTimeout(() => {
                console.log('🔄 Real-time update triggered');
                generateReport();
                isUpdating = false;
            }, 300);
        }

        // Refresh button
        document.querySelector('.btn-refresh').addEventListener('click', function() {
            clearTimeout(updateTimeout);
            generateReport();
            this.innerHTML = '🔄 Refreshing...';
            setTimeout(() => {
                this.innerHTML = '🔄 Refresh Data';
            }, 1000);
        });

        // Generate button
        document.querySelector('.btn-generate').addEventListener('click', function() {
            console.log('Generate button clicked');
            clearTimeout(updateTimeout);
            generateReport();
        });

        // Report Type filter - real-time
        document.querySelector('.filter-group select').addEventListener('change', scheduleUpdate);

        // Branch filter - real-time
        document.querySelectorAll('.filter-group select')[1].addEventListener('change', scheduleUpdate);

        // Year filter - real-time
        document.querySelectorAll('.filter-group select')[2].addEventListener('change', scheduleUpdate);

        // Date range filters - real-time with input event (while typing/selecting)
        document.getElementById('startDate').addEventListener('change', scheduleUpdate);
        document.getElementById('startDate').addEventListener('input', scheduleUpdate);

        document.getElementById('endDate').addEventListener('change', scheduleUpdate);
        document.getElementById('endDate').addEventListener('input', scheduleUpdate);

        // Export buttons
        document.querySelector('.btn-pdf').addEventListener('click', function() {
            exportToPDF();
        });

        document.querySelector('.btn-excel').addEventListener('click', function() {
            exportToCSV();
        });

        document.querySelector('.btn-print').addEventListener('click', function() {
            window.print();
        });

        document.querySelector('.btn-download').addEventListener('click', function() {
            exportToJSON();
        });

        function exportToCSV() {
            let csv = 'Patient,Contact,Birthday,Age,Status,Date\n';
            const dataToExport = reportsData.lastFilteredData.length > 0 ? reportsData.lastFilteredData : reportsData.patients;
            dataToExport.forEach(apt => {
                csv += `"${apt.patient}","${apt.contact}","${apt.birthday}","${apt.age}","${apt.status}","${apt.created_at}"\n`;
            });

            const link = document.createElement('a');
            link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
            link.download = 'appointments-report.csv';
            link.click();
            console.log('CSV exported with', dataToExport.length, 'records');
        }

        function exportToJSON() {
            const dataToExport = reportsData.lastFilteredData.length > 0 ? reportsData.lastFilteredData : reportsData.patients;
            const dataStr = JSON.stringify(dataToExport, null, 2);
            const link = document.createElement('a');
            link.href = 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr);
            link.download = 'appointments-report.json';
            link.click();
            console.log('JSON exported with', dataToExport.length, 'records');
        }

        function exportToPDF() {
            const dataToExport = reportsData.lastFilteredData.length > 0 ? reportsData.lastFilteredData : reportsData.patients;
            const printWindow = window.open('', '', 'height=600,width=800');
            const reportHtml = `
                <h1>Appointments Report</h1>
                <p>Records: ${dataToExport.length}</p>
                <table border="1" style="width:100%; border-collapse: collapse;">
                    <tr style="background: #f0f0f0;">
                        <th style="padding: 8px;">Patient</th>
                        <th style="padding: 8px;">Contact</th>
                        <th style="padding: 8px;">Status</th>
                        <th style="padding: 8px;">Date</th>
                    </tr>
                    ${dataToExport.map(apt => `
                        <tr>
                            <td style="padding: 8px;">${apt.patient}</td>
                            <td style="padding: 8px;">${apt.contact}</td>
                            <td style="padding: 8px;">${apt.status}</td>
                            <td style="padding: 8px;">${apt.created_at}</td>
                        </tr>
                    `).join('')}
                </table>
            `;
            printWindow.document.write(reportHtml);
            printWindow.document.close();
            console.log('PDF prepared with', dataToExport.length, 'records');
        }
    </script>
@endsection