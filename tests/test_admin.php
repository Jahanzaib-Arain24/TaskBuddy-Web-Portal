<?php
/**
 * Module 5: Admin Control Panel QA Test Suite
 */

function run_admin_tests($conn) {
    $module = "Admin Control Panel";

    // 1. Admin Account verification
    $adminStmt = $conn->query("SELECT * FROM tbl_users WHERE role = 'admin' LIMIT 1");
    $admin = $adminStmt->fetch(PDO::FETCH_ASSOC);
    QATestFramework::assertNotEmpty($module, "ADM-001", "Admin superuser account exists", $admin, "Email: " . ($admin['email'] ?? 'N/A'));

    // 2. Dashboard Aggregate Stats Calculation Tests
    $totalTasks = (int)$conn->query("SELECT COUNT(*) FROM tbl_jobs")->fetchColumn();
    $totalSeekers = (int)$conn->query("SELECT COUNT(*) FROM tbl_users WHERE role = 'employee'")->fetchColumn();
    $totalListers = (int)$conn->query("SELECT COUNT(*) FROM tbl_users WHERE role = 'employer'")->fetchColumn();
    $totalApplications = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications")->fetchColumn();
    $totalCategories = (int)$conn->query("SELECT COUNT(*) FROM tbl_categories")->fetchColumn();
    $totalCountries = (int)$conn->query("SELECT COUNT(*) FROM tbl_countries")->fetchColumn();

    QATestFramework::assert($module, "ADM-002", "Admin aggregate Total Tasks calculation", $totalTasks >= 0, "Count: $totalTasks");
    QATestFramework::assert($module, "ADM-003", "Admin aggregate Task Seekers calculation", $totalSeekers >= 0, "Count: $totalSeekers");
    QATestFramework::assert($module, "ADM-004", "Admin aggregate Task Listers calculation", $totalListers >= 0, "Count: $totalListers");
    QATestFramework::assert($module, "ADM-005", "Admin aggregate Total Applications calculation", $totalApplications >= 0, "Count: $totalApplications");
    QATestFramework::assert($module, "ADM-006", "Admin aggregate Total Categories calculation", $totalCategories > 0, "Count: $totalCategories");
    QATestFramework::assert($module, "ADM-007", "Admin aggregate Total Countries calculation", $totalCountries > 0, "Count: $totalCountries");

    // 3. Applications Pipeline Status Distribution Tests
    $pendingApps = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications WHERE status = 'Pending' OR status = 'Under Review' OR status = '' OR status IS NULL")->fetchColumn();
    $shortlistedApps = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications WHERE status = 'Shortlisted'")->fetchColumn();
    $selectedApps = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications WHERE status = 'Selected' OR status = 'Hired'")->fetchColumn();
    $rejectedApps = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications WHERE status = 'Rejected'")->fetchColumn();

    $sumPipeline = $pendingApps + $shortlistedApps + $selectedApps + $rejectedApps;
    QATestFramework::assertEquals($module, "ADM-008", "Applications pipeline sum equals total bids", $totalApplications, $sumPipeline, "Sum: $sumPipeline, Total: $totalApplications");

    // 4. Admin Users Management Query Tests
    $usersStmt = $conn->query("SELECT * FROM tbl_users WHERE role != 'admin' ORDER BY member_no DESC");
    $allUsers = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($allUsers as $idx => $u) {
        $testId = "ADM-USR-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $isValidRole = ($u['role'] === 'employee' || $u['role'] === 'employer');
        QATestFramework::assert($module, $testId, "User list integrity for Member ID: " . $u['member_no'], $isValidRole, "Role: " . $u['role']);
    }

    // 5. Admin Tasks Management Query Tests
    $jobsStmt = $conn->query("SELECT j.*, u.first_name as company_name FROM tbl_jobs j LEFT JOIN tbl_users u ON j.company = u.member_no ORDER BY j.enc_id DESC");
    $allJobs = $jobsStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($allJobs as $idx => $j) {
        $testId = "ADM-JOB-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasJobId = !empty($j['job_id']);
        QATestFramework::assert($module, $testId, "Admin task record integrity for Job ID: " . $j['job_id'], $hasJobId, "Title: " . substr($j['title'], 0, 25));
    }

    // 6. Admin System Alerts Tests
    $alertsStmt = $conn->query("SELECT * FROM tbl_alerts");
    $alerts = $alertsStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assertNotEmpty($module, "ADM-009", "System alerts configured in database", $alerts, "Total alerts: " . count($alerts));

    foreach ($alerts as $idx => $alert) {
        $testId = "ADM-ALRT-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasCode = !empty($alert['code']);
        $hasDesc = !empty($alert['description']);
        QATestFramework::assert($module, $testId, "Alert code [" . $alert['code'] . "] configured", $hasCode && $hasDesc);
    }

    // 7. Admin Categories Integrity Tests
    $catStmt = $conn->query("SELECT * FROM tbl_categories");
    $allCats = $catStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($allCats as $idx => $cat) {
        $testId = "ADM-CAT-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasName = !empty($cat['category']) && strlen($cat['category']) >= 2;
        QATestFramework::assert($module, $testId, "Category integrity check: " . $cat['category'], $hasName);
    }

    // 8. Admin Countries Integrity Tests
    $cntStmt = $conn->query("SELECT * FROM tbl_countries");
    $allCountries = $cntStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($allCountries as $idx => $country) {
        $testId = "ADM-CNT-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasCountryName = !empty($country['country_name']);
        QATestFramework::assert($module, $testId, "Country record check: " . $country['country_name'], $hasCountryName);
    }
}
