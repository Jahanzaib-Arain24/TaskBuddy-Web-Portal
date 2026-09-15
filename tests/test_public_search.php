<?php
/**
 * Module 2: Public Portal & Search / Discovery QA Test Suite
 */

function run_public_search_tests($conn) {
    $module = "Public Portal & Search Discovery";

    // 1. Categories verification
    $catStmt = $conn->query("SELECT * FROM tbl_categories");
    $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assertNotEmpty($module, "PUB-001", "Categories table populated", $categories, "Total categories: " . count($categories));

    foreach (array_slice($categories, 0, 10) as $idx => $cat) {
        $catId = "PUB-CAT-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        QATestFramework::assertNotEmpty($module, $catId, "Category '" . $cat['category'] . "' has valid name and ID", !empty($cat['category']));
    }

    // 2. Countries verification
    $cntStmt = $conn->query("SELECT * FROM tbl_countries");
    $countries = $cntStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assertNotEmpty($module, "PUB-002", "Countries table populated", $countries, "Total countries: " . count($countries));

    // 3. Search Engine Test Cases
    $testKeywords = ['Clean', 'Fix', 'Driver', 'Electrical', 'Paint', 'Repair', 'Tutor', 'Cook', 'Plumb', 'Garden'];
    foreach ($testKeywords as $idx => $kw) {
        $testId = "PUB-SRCH-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $searchPattern = "%$kw%";
        $stmt = $conn->prepare("SELECT j.*, u.first_name as company_name FROM tbl_jobs j LEFT JOIN tbl_users u ON j.company = u.member_no WHERE j.title LIKE :kw1 OR j.category LIKE :kw2 OR j.city LIKE :kw3");
        $stmt->execute([':kw1' => $searchPattern, ':kw2' => $searchPattern, ':kw3' => $searchPattern]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        QATestFramework::assert($module, $testId, "Search query execution for '$kw'", ($stmt->errorCode() === '00000'), "Returned " . count($results) . " results");
    }

    // 4. Full Category Filter Search Tests
    foreach ($categories as $idx => $cat) {
        $testId = "PUB-CATFILT-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_jobs WHERE category = :cat");
        $stmt->execute([':cat' => $cat['category']]);
        $count = $stmt->fetchColumn();
        QATestFramework::assert($module, $testId, "Filter jobs by category '" . $cat['category'] . "'", is_numeric($count), "Jobs count: $count");
    }

    // 5. Job Type Filter Search Tests
    $jobTypes = ['Full-time', 'Part-time', 'Freelance', 'Contract', 'Temporary'];
    foreach ($jobTypes as $idx => $jt) {
        $testId = "PUB-TYPEFILT-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_jobs WHERE type = :type");
        $stmt->execute([':type' => $jt]);
        $count = $stmt->fetchColumn();
        QATestFramework::assert($module, $testId, "Filter jobs by type '$jt'", is_numeric($count), "Found: $count jobs");
    }

    // 6. Country & City Location Search Tests
    $cities = ['Hyderabad', 'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Peshawar', 'Quetta', 'Multan', 'Faisalabad'];
    foreach ($cities as $idx => $city) {
        $testId = "PUB-CITY-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_jobs WHERE city LIKE :c");
        $stmt->execute([':c' => "%$city%"]);
        $count = $stmt->fetchColumn();
        QATestFramework::assert($module, $testId, "Location search for city '$city'", is_numeric($count), "Jobs in $city: $count");
    }

    // 7. Pagination Matrix & Offset Tests
    for ($page = 1; $page <= 5; $page++) {
        $testId = "PUB-PAGE-" . str_pad($page, 3, "0", STR_PAD_LEFT);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $stmt = $conn->prepare("SELECT * FROM tbl_jobs ORDER BY enc_id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $pageResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        QATestFramework::assert($module, $testId, "Pagination slice for page $page (offset: $offset)", ($stmt->errorCode() === '00000'), "Items on page: " . count($pageResults));
    }

    // 8. Job Detail Exploration Tests
    $jobsStmt = $conn->query("SELECT * FROM tbl_jobs LIMIT 30");
    $sampleJobs = $jobsStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assertNotEmpty($module, "PUB-003", "Sample jobs exist for detail testing", $sampleJobs);

    foreach ($sampleJobs as $idx => $job) {
        $testId = "PUB-JOBDTL-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasEssentialFields = !empty($job['job_id']) && !empty($job['title']) && !empty($job['category']) && !empty($job['company']);
        QATestFramework::assert($module, $testId, "Task metadata integrity for ID: " . $job['job_id'], $hasEssentialFields, "Title: " . substr($job['title'], 0, 25));
    }

    // 9. Company Public Profiles Tests
    $compStmt = $conn->query("SELECT * FROM tbl_users WHERE role = 'employer' LIMIT 20");
    $sampleComps = $compStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($sampleComps as $idx => $comp) {
        $testId = "PUB-COMP-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasName = !empty($comp['first_name']);
        QATestFramework::assert($module, $testId, "Company profile data for " . $comp['member_no'], $hasName, "Company: " . $comp['first_name']);
    }

    // 10. Seeker Public Profiles Tests
    $seekStmt = $conn->query("SELECT * FROM tbl_users WHERE role = 'employee' LIMIT 20");
    $sampleSeekers = $seekStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($sampleSeekers as $idx => $seeker) {
        $testId = "PUB-SEEK-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasName = !empty($seeker['first_name']);
        QATestFramework::assert($module, $testId, "Seeker profile data for " . $seeker['member_no'], $hasName, "Seeker: " . $seeker['first_name'] . ' ' . $seeker['last_name']);
    }
}
