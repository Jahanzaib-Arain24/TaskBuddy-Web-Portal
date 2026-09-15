<?php
/**
 * Module 3: Task Seeker (Employee) QA Test Suite
 */

function run_seeker_tests($conn) {
    $module = "Task Seeker (Candidate) Portal";

    // 1. Seeker Profile Data Validation
    $seekersStmt = $conn->query("SELECT * FROM tbl_users WHERE role = 'employee'");
    $seekers = $seekersStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assertNotEmpty($module, "SEEK-001", "Task Seekers registered in system", $seekers, "Total seekers: " . count($seekers));

    foreach ($seekers as $idx => $s) {
        $testId = "SEEK-PROF-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasMemberNo = !empty($s['member_no']) && strpos($s['member_no'], 'EM') === 0;
        QATestFramework::assert($module, $testId, "Seeker Member No format (" . $s['member_no'] . ")", $hasMemberNo);
    }

    // 2. Academic Qualification Tests
    $acadStmt = $conn->query("SELECT * FROM tbl_academic_qualification");
    $acads = $acadStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assert($module, "SEEK-002", "Academic qualifications table queried successfully", ($acadStmt->errorCode() === '00000'), "Total records: " . count($acads));

    foreach ($acads as $idx => $ac) {
        $testId = "SEEK-ACAD-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $isValidAcad = !empty($ac['member_no']) && (!empty($ac['institution']) || !empty($ac['course']) || !empty($ac['level']));
        QATestFramework::assert($module, $testId, "Academic record integrity for ID " . $ac['id'], $isValidAcad);
    }

    // 3. Experience Records Tests
    $expStmt = $conn->query("SELECT * FROM tbl_experience");
    $experiences = $expStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assert($module, "SEEK-003", "Experience records table queried successfully", ($expStmt->errorCode() === '00000'), "Total records: " . count($experiences));

    foreach ($experiences as $idx => $ex) {
        $testId = "SEEK-EXP-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $isValidExp = !empty($ex['member_no']) && !empty($ex['institution']);
        QATestFramework::assert($module, $testId, "Experience record integrity for ID " . $ex['id'], $isValidExp, "Institution: " . ($ex['institution'] ?? 'N/A'));
    }

    // 4. Training Records Tests
    $trainStmt = $conn->query("SELECT * FROM tbl_training");
    $trainings = $trainStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assert($module, "SEEK-004", "Training records table queried successfully", ($trainStmt->errorCode() === '00000'), "Total training records: " . count($trainings));

    foreach ($trainings as $idx => $tr) {
        $testId = "SEEK-TRN-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $isValidTr = !empty($tr['member_no']) && !empty($tr['training']);
        QATestFramework::assert($module, $testId, "Training record integrity for ID " . $tr['id'], $isValidTr, "Training: " . ($tr['training'] ?? 'N/A'));
    }

    // 5. Language Proficiencies Tests
    $langStmt = $conn->query("SELECT * FROM tbl_language");
    $languages = $langStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assert($module, "SEEK-005", "Language proficiencies table queried successfully", ($langStmt->errorCode() === '00000'), "Total languages: " . count($languages));

    foreach ($languages as $idx => $lang) {
        $testId = "SEEK-LANG-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $isValidLang = !empty($lang['member_no']) && !empty($lang['language']);
        QATestFramework::assert($module, $testId, "Language record integrity for ID " . $lang['id'], $isValidLang, "Language: " . ($lang['language'] ?? 'N/A'));
    }

    // 6. Professional Qualifications Tests
    $profStmt = $conn->query("SELECT * FROM tbl_professional_qualification");
    $profs = $profStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assert($module, "SEEK-006", "Professional qualifications table queried successfully", ($profStmt->errorCode() === '00000'), "Total records: " . count($profs));

    foreach ($profs as $idx => $prof) {
        $testId = "SEEK-PROFQUAL-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $isValidProf = !empty($prof['member_no']) && !empty($prof['title']);
        QATestFramework::assert($module, $testId, "Professional qualification integrity for ID " . $prof['id'], $isValidProf, "Title: " . ($prof['title'] ?? 'N/A'));
    }

    // 7. Seeker Applications & Bid History Tests
    $appStmt = $conn->query("SELECT a.*, j.title, j.company FROM tbl_job_applications a LEFT JOIN tbl_jobs j ON a.job_id = j.job_id");
    $applications = $appStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assertNotEmpty($module, "SEEK-007", "Applications / Bids exist in system", $applications, "Total bids: " . count($applications));

    $validStatuses = ['Pending', 'Under Review', 'Shortlisted', 'Selected', 'Hired', 'Rejected'];

    foreach ($applications as $idx => $app) {
        $testId = "SEEK-APP-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $statusValid = in_array($app['status'] ?: 'Pending', $validStatuses);
        $hasJobId = !empty($app['job_id']);
        QATestFramework::assert($module, $testId, "Application #" . $app['id'] . " status (" . ($app['status'] ?: 'Pending') . ") validity", $statusValid && $hasJobId, "Task ID: " . $app['job_id']);
    }

    // 8. Application Duplicate Prevention Logic Test
    $dupCheckStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_job_applications WHERE member_no = :m AND job_id = :j");
    $testSeeker = !empty($seekers[0]) ? $seekers[0]['member_no'] : 'EMTEST999';
    $dupCheckStmt->execute([':m' => $testSeeker, ':j' => 'JBTEST999']);
    $dupCount = $dupCheckStmt->fetchColumn();
    QATestFramework::assert($module, "SEEK-008", "Duplicate application detection query operates correctly", is_numeric($dupCount));
}
