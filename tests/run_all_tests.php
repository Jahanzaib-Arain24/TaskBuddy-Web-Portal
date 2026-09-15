<?php
/**
 * Master QA Test Runner & HTML Report Generator
 * Executes all 6 modules and generates a comprehensive QA audit report.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/framework.php';
require_once __DIR__ . '/../constants/db_config.php';
require_once __DIR__ . '/../constants/settings.php';
require_once __DIR__ . '/../constants/csrf.php';

require_once __DIR__ . '/test_auth.php';
require_once __DIR__ . '/test_public_search.php';
require_once __DIR__ . '/test_seeker.php';
require_once __DIR__ . '/test_employer.php';
require_once __DIR__ . '/test_admin.php';
require_once __DIR__ . '/test_security_owasp.php';

// Initialize Framework
QATestFramework::init();
$conn = get_db_connection();

echo "========================================================\n";
echo "       TASKBUDDY AUTOMATED QA TEST RUNNER ENGINE        \n";
echo "========================================================\n";
echo "Starting test execution across all core modules...\n\n";

// Execute all test suites
run_auth_tests($conn);
run_public_search_tests($conn);
run_seeker_tests($conn);
run_employer_tests($conn);
run_admin_tests($conn);
run_security_owasp_tests($conn);

$summary = QATestFramework::getSummary();

echo "--------------------------------------------------------\n";
echo "Total Tests Run:  " . $summary['total'] . "\n";
echo "Passed Tests:     " . $summary['passed'] . " [PASS]\n";
echo "Failed Tests:     " . $summary['failed'] . " [FAIL]\n";
echo "Pass Rate:        " . $summary['pass_rate'] . "%\n";
echo "Execution Time:   " . $summary['duration_seconds'] . " seconds\n";
echo "========================================================\n\n";

if ($summary['failed'] > 0) {
    echo "FAILED TEST DETAILS:\n";
    foreach ($summary['results'] as $res) {
        if ($res['status'] === 'FAIL') {
            echo " [FAIL] " . $res['id'] . " | " . $res['module'] . " | " . $res['description'] . "\n";
            echo "        Details: " . $res['details'] . "\n";
        }
    }
    echo "--------------------------------------------------------\n\n";
}

// Generate Premium HTML Report
$reportHtml = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskBuddy QA Test Execution Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #0f172a;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
        }
        body {
            font-family: "Plus Jakarta Sans", -apple-system, sans-serif;
            background: var(--bg);
            color: #334155;
            margin: 0;
            padding: 30px 20px;
        }
        .container {
            max-width: 1280px;
            margin: 0 auto;
        }
        .header-card {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            border-radius: 18px;
            padding: 35px 40px;
            color: #ffffff;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px -5px rgba(49, 46, 129, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }
        .stat-val {
            font-size: 32px;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
            margin-bottom: 6px;
        }
        .stat-lbl {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);
            overflow: hidden;
        }
        .table-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th {
            background: #f8fafc;
            padding: 14px 20px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            font-weight: 700;
            border-bottom: 1px solid var(--border);
        }
        td {
            padding: 14px 20px;
            font-size: 13.5px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
        }
        .badge-pass { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-fail { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .filter-btn {
            background: #f1f5f9;
            border: 1px solid var(--border);
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .filter-btn.active {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-card">
            <div>
                <span style="background: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">System QA Audit Report</span>
                <h1 style="margin: 12px 0 6px 0; font-size: 30px; font-weight: 800;">TaskBuddy System Test Execution Report</h1>
                <p style="margin: 0; opacity: 0.85; font-size: 14px;">Automated End-to-End Test Suite Execution on Localhost Environment</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 38px; font-weight: 800; color: #34d399;">' . $summary['pass_rate'] . '%</div>
                <div style="font-size: 12px; opacity: 0.8; text-transform: uppercase; font-weight: 700;">Overall Pass Rate</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card" style="border-left: 4px solid var(--primary);">
                <div class="stat-val">' . $summary['total'] . '</div>
                <div class="stat-lbl">Total Tests Executed</div>
            </div>
            <div class="stat-card" style="border-left: 4px solid var(--success);">
                <div class="stat-val" style="color: var(--success);">' . $summary['passed'] . '</div>
                <div class="stat-lbl">Passed Tests</div>
            </div>
            <div class="stat-card" style="border-left: 4px solid var(--danger);">
                <div class="stat-val" style="color: var(--danger);">' . $summary['failed'] . '</div>
                <div class="stat-lbl">Failed Tests</div>
            </div>
            <div class="stat-card" style="border-left: 4px solid var(--warning);">
                <div class="stat-val" style="color: var(--warning);">' . $summary['duration_seconds'] . 's</div>
                <div class="stat-lbl">Execution Duration</div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: var(--dark);">Test Case Execution Details</h3>
                <div style="display: flex; gap: 8px;">
                    <button class="filter-btn active" onclick="filterTable(\'all\')">All (' . $summary['total'] . ')</button>
                    <button class="filter-btn" onclick="filterTable(\'PASS\')"><i class="fa fa-check text-success"></i> Passed (' . $summary['passed'] . ')</button>
                    <button class="filter-btn" onclick="filterTable(\'FAIL\')"><i class="fa fa-times text-danger"></i> Failed (' . $summary['failed'] . ')</button>
                </div>
            </div>

            <table id="testTable">
                <thead>
                    <tr>
                        <th>Test ID</th>
                        <th>Module</th>
                        <th>Test Description</th>
                        <th>Status</th>
                        <th>Execution Time</th>
                    </tr>
                </thead>
                <tbody>';

foreach ($summary['results'] as $res) {
    $badgeClass = ($res['status'] === 'PASS') ? 'badge-pass' : 'badge-fail';
    $icon = ($res['status'] === 'PASS') ? 'fa-check' : 'fa-times';
    $reportHtml .= '<tr class="test-row" data-status="' . $res['status'] . '">
        <td style="font-family: monospace; font-weight: 700; color: var(--primary);">' . htmlspecialchars($res['id']) . '</td>
        <td><strong>' . htmlspecialchars($res['module']) . '</strong></td>
        <td>
            ' . htmlspecialchars($res['description']) . '
            ' . (!empty($res['details']) ? '<div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">' . htmlspecialchars($res['details']) . '</div>' : '') . '
        </td>
        <td><span class="badge ' . $badgeClass . '"><i class="fa ' . $icon . '"></i> ' . $res['status'] . '</span></td>
        <td style="font-family: monospace; font-size: 12px; color: #64748b;">' . $res['time'] . 's</td>
    </tr>';
}

$reportHtml .= '</tbody>
            </table>
        </div>
    </div>

    <script>
        function filterTable(status) {
            document.querySelectorAll(".filter-btn").forEach(btn => btn.classList.remove("active"));
            event.target.classList.add("active");
            
            document.querySelectorAll(".test-row").forEach(row => {
                if (status === "all" || row.getAttribute("data-status") === status) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>';

$reportPath = __DIR__ . '/qa_test_report.html';
file_put_contents($reportPath, $reportHtml);
echo "Full HTML QA Audit Report written to: tests/qa_test_report.html\n";
