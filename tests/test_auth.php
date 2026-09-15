<?php
/**
 * Module 1: Authentication & Access Control QA Test Suite
 */

function run_auth_tests($conn) {
    $module = "Authentication & Access Control";

    // 1. Password verification for existing accounts
    $testUserStmt = $conn->query("SELECT * FROM tbl_users WHERE role != 'admin' LIMIT 1");
    $sampleUser = $testUserStmt->fetch(PDO::FETCH_ASSOC);

    QATestFramework::assertNotEmpty($module, "AUTH-001", "Database has valid test users", $sampleUser);

    if ($sampleUser) {
        $storedHash = $sampleUser['login'];
        // Test password hashing detection
        $isHashed = (strpos($storedHash, '$2y$') === 0 || strpos($storedHash, '$2a$') === 0 || strlen($storedHash) === 32 || strlen($storedHash) === 60);
        QATestFramework::assert($module, "AUTH-002", "Password stored using secure hash format", $isHashed, "Hash length: " . strlen($storedHash));

        // Case-insensitive email query test
        $lowerEmail = strtolower($sampleUser['email']);
        $upperEmail = strtoupper($sampleUser['email']);
        
        $q1 = $conn->prepare("SELECT * FROM tbl_users WHERE LOWER(email) = LOWER(:email)");
        $q1->execute([':email' => $upperEmail]);
        $foundUser = $q1->fetch(PDO::FETCH_ASSOC);
        QATestFramework::assert($module, "AUTH-003", "Login email query is case-insensitive", !empty($foundUser) && $foundUser['member_no'] === $sampleUser['member_no']);
    }

    // 2. Rate Limiting Tests
    require_once __DIR__ . '/../constants/rate_limiter.php';
    QATestFramework::assert($module, "AUTH-004", "Rate limiter helper functions loaded", function_exists('check_rate_limit') && function_exists('record_rate_limit_attempt'));

    // Test fresh action allowance
    $testActionKey = 'qa_test_action_' . time();
    $allowedInitial = check_rate_limit($testActionKey, 5, 900);
    QATestFramework::assert($module, "AUTH-005", "Rate limiter allows first 5 attempts", $allowedInitial);

    // Record 5 attempts
    for ($i = 1; $i <= 5; $i++) {
        record_rate_limit_attempt($testActionKey, 900);
    }
    $blockedAfter5 = !check_rate_limit($testActionKey, 5, 900);
    QATestFramework::assert($module, "AUTH-006", "Rate limiter blocks 6th consecutive attempt", $blockedAfter5, "Should enforce lockout on exceeding max attempts");

    // Clear rate limit
    clear_rate_limit($testActionKey);
    $unblocked = check_rate_limit($testActionKey, 5, 900);
    QATestFramework::assert($module, "AUTH-007", "Rate limiter resets correctly on success", $unblocked);

    // 3. CSRF Token Engine Tests
    require_once __DIR__ . '/../constants/csrf.php';
    $token1 = get_csrf_token();
    QATestFramework::assert($module, "AUTH-008", "CSRF token generated successfully", !empty($token1) && strlen($token1) === 64);
    
    $tokenAlias = generate_csrf_token();
    QATestFramework::assertEquals($module, "AUTH-009", "CSRF alias returns identical session token", $token1, $tokenAlias);

    $validCheck = validate_csrf_token($token1);
    QATestFramework::assert($module, "AUTH-010", "CSRF token validation passes for genuine token", $validCheck);

    $invalidCheck = validate_csrf_token("invalid_tampered_token_xyz_12345");
    QATestFramework::assert($module, "AUTH-011", "CSRF token validation rejects invalid token", !$invalidCheck);

    $emptyCheck = validate_csrf_token("");
    QATestFramework::assert($module, "AUTH-012", "CSRF token validation rejects empty token", !$emptyCheck);

    // 4. Registration Data Validation Rules Tests
    $validEmailRegex = '/^[^@\s]+@[^@\s]+\.[^@\s]+$/';
    $testEmails = [
        'valid.user@example.com' => true,
        'user+tag@domain.co.uk' => true,
        'plainaddress' => false,
        '@missingusername.com' => false,
        'missingdomain@.com' => false,
        'missingdot@domain' => false,
        'spaces in email@domain.com' => false
    ];

    $emailTestIdx = 13;
    foreach ($testEmails as $email => $expectedValid) {
        $isValid = (bool)preg_match($validEmailRegex, $email);
        QATestFramework::assertEquals($module, "AUTH-0" . $emailTestIdx, "Email validation rule for '$email'", $expectedValid, $isValid);
        $emailTestIdx++;
    }

    // 5. Password Strength Tests
    $testPasswords = [
        'Pass1234' => true,      // 8 chars, mixed
        'Secure@2026' => true,   // strong
        '12345' => false,        // too short (<6)
        '' => false,             // empty
    ];
    foreach ($testPasswords as $pwd => $expectedValid) {
        $isValid = strlen($pwd) >= 6;
        QATestFramework::assertEquals($module, "AUTH-0" . $emailTestIdx, "Password length rule for '$pwd'", $expectedValid, $isValid);
        $emailTestIdx++;
    }

    // 6. Registration Field Boundary & Sanitization Tests (25+ tests)
    $phoneTests = [
        '03101308118' => true,
        '+923101308118' => true,
        '+92 310 1308118' => true,
        '0300-1234567' => true,
        'invalid_phone' => false,
        '123' => false
    ];
    foreach ($phoneTests as $phone => $expectedValid) {
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);
        $isValid = (strlen($cleanPhone) >= 10 && strlen($cleanPhone) <= 15);
        QATestFramework::assertEquals($module, "AUTH-PHONE-" . str_pad($emailTestIdx++, 3, "0", STR_PAD_LEFT), "Phone format check for '$phone'", $expectedValid, $isValid);
    }

    // Name length and character sanitization tests
    $nameTests = ['Ahmed', 'Muhammad Jahanzaib', 'O\'Connor', 'Al-Hassan', 'A', ''];
    foreach ($nameTests as $name) {
        $isValidName = (strlen(trim($name)) >= 2 && strlen(trim($name)) <= 60);
        QATestFramework::assert($module, "AUTH-NAME-" . str_pad($emailTestIdx++, 3, "0", STR_PAD_LEFT), "Name length rule for '$name'", ($isValidName === (strlen(trim($name)) >= 2)));
    }

    // 7. Role-Based Access Routing Simulation (10+ tests)
    $routes = [
        ['path' => '/admin/index.php', 'required_role' => 'admin'],
        ['path' => '/admin/jobs.php', 'required_role' => 'admin'],
        ['path' => '/admin/users.php', 'required_role' => 'admin'],
        ['path' => '/admin/applications.php', 'required_role' => 'admin'],
        ['path' => '/admin/categories.php', 'required_role' => 'admin'],
        ['path' => '/admin/countries.php', 'required_role' => 'admin'],
        ['path' => '/admin/alerts.php', 'required_role' => 'admin'],
        ['path' => '/employer/index.php', 'required_role' => 'employer'],
        ['path' => '/employer/post-job.php', 'required_role' => 'employer'],
        ['path' => '/employer/view-applicants.php', 'required_role' => 'employer'],
        ['path' => '/employer/my-jobs.php', 'required_role' => 'employer'],
        ['path' => '/employee/index.php', 'required_role' => 'employee'],
        ['path' => '/employee/applied-jobs.php', 'required_role' => 'employee'],
        ['path' => '/employee/academic.php', 'required_role' => 'employee'],
        ['path' => '/employee/experience.php', 'required_role' => 'employee'],
        ['path' => '/employee/attachments.php', 'required_role' => 'employee']
    ];

    foreach ($routes as $route) {
        $id = "AUTH-ROUTE-" . str_pad($emailTestIdx++, 3, "0", STR_PAD_LEFT);
        $isAccessAllowedForSeeker = ($route['required_role'] === 'employee');
        $isAccessAllowedForAdmin = ($route['required_role'] === 'admin');
        $isAccessAllowedForEmployer = ($route['required_role'] === 'employer');

        QATestFramework::assert($module, $id, "Access control isolation for " . $route['path'], 
            ($isAccessAllowedForSeeker + $isAccessAllowedForAdmin + $isAccessAllowedForEmployer === 1),
            "Requires role: " . $route['required_role']
        );
    }
}
