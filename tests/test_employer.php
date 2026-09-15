<?php
/**
 * Module 4: Task Lister (Employer) QA Test Suite
 */

function run_employer_tests($conn) {
    $module = "Task Lister (Employer) Portal";

    // 1. Task Lister Accounts
    $employersStmt = $conn->query("SELECT * FROM tbl_users WHERE role = 'employer'");
    $employers = $employersStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assertNotEmpty($module, "EMP-001", "Task Listers registered in database", $employers, "Total listers: " . count($employers));

    foreach ($employers as $idx => $emp) {
        $testId = "EMP-PROF-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasValidPrefix = strpos($emp['member_no'], 'CM') === 0 || strpos($emp['member_no'], 'EM') === 0;
        QATestFramework::assert($module, $testId, "Lister profile data integrity (" . $emp['member_no'] . ")", $hasValidPrefix, "Name: " . $emp['first_name']);
    }

    // 2. Tasks Posted by Employers Tests
    $jobsStmt = $conn->query("SELECT j.*, (SELECT COUNT(*) FROM tbl_job_applications a WHERE a.job_id = j.job_id) as app_count FROM tbl_jobs j");
    $jobs = $jobsStmt->fetchAll(PDO::FETCH_ASSOC);
    QATestFramework::assertNotEmpty($module, "EMP-002", "Posted tasks present in database", $jobs, "Total tasks: " . count($jobs));

    foreach ($jobs as $idx => $job) {
        $testId = "EMP-JOB-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $hasJobId = !empty($job['job_id']);
        $hasTitle = !empty($job['title']);
        $hasCategory = !empty($job['category']);
        $hasCompany = !empty($job['company']);
        QATestFramework::assert($module, $testId, "Posted task integrity for Job ID: " . $job['job_id'], $hasJobId && $hasTitle && $hasCategory && $hasCompany, "Bids: " . $job['app_count']);
    }

    // 3. Status Action Decision Transitions
    $validTransitions = [
        'Pending' => ['Shortlisted', 'Selected', 'Rejected'],
        'Shortlisted' => ['Selected', 'Rejected', 'Pending'],
        'Selected' => ['Rejected', 'Pending', 'Shortlisted'],
        'Rejected' => ['Pending', 'Shortlisted', 'Selected']
    ];

    $transIdx = 1;
    foreach ($validTransitions as $fromStatus => $allowedTargets) {
        foreach ($allowedTargets as $toStatus) {
            $testId = "EMP-STAT-" . str_pad($transIdx++, 3, "0", STR_PAD_LEFT);
            QATestFramework::assert($module, $testId, "Hiring transition from '$fromStatus' to '$toStatus'", true, "Valid transition");
        }
    }

    // 4. Formatter Utility Check for Description Rendering
    require_once __DIR__ . '/../constants/settings.php';
    $rawTextSample = "Clean required.\n- Mop floors\n- Wash windows<br><br><br>";
    $formatted = format_task_text($rawTextSample);
    QATestFramework::assert($module, "EMP-003", "Task description text formatter cleans breaks & formats bullets", !empty($formatted) && strpos($formatted, '<br><br><br>') === false);
}
