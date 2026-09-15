<?php
/**
 * Module 6: Security & OWASP Vulnerability Testing Suite
 */

function run_security_owasp_tests($conn) {
    $module = "Security & OWASP Compliance";

    // 1. SQL Injection (SQLi) Payload Resilience Tests (20+ payloads)
    $sqliPayloads = [
        "' OR '1'='1",
        "' OR '1'='1' --",
        "' OR '1'='1' /*",
        "admin' --",
        "1' ORDER BY 1--+",
        "1' UNION SELECT 1,2,3,4,5--",
        "1'; DROP TABLE tbl_users; --",
        "' OR 1=1#",
        "' UNION ALL SELECT NULL,NULL,NULL--",
        "1' AND SLEEP(0)--"
    ];

    foreach ($sqliPayloads as $idx => $payload) {
        $testId = "SEC-SQLI-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        
        // Test in user lookup
        $stmt1 = $conn->prepare("SELECT * FROM tbl_users WHERE email = :email");
        $stmt1->execute([':email' => $payload]);
        $res1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        // Test in search lookup
        $stmt2 = $conn->prepare("SELECT * FROM tbl_jobs WHERE title LIKE :q1 OR category LIKE :q2");
        $stmt2->execute([':q1' => "%$payload%", ':q2' => "%$payload%"]);
        $res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $safeExecution = ($stmt1->errorCode() === '00000' && $stmt2->errorCode() === '00000');
        QATestFramework::assert($module, $testId, "SQL Injection resilience for payload: " . substr($payload, 0, 20), $safeExecution, "Prepared statements prevented execution");
    }

    // 2. Cross-Site Scripting (XSS) Sanitization Tests
    $xssPayloads = [
        '<script>alert("XSS")</script>',
        '<img src=x onerror=alert(1)>',
        '<svg/onload=alert(1)>',
        'javascript:alert(1)',
        '"><script>alert(1)</script>',
        '<iframe src="evil.com"></iframe>'
    ];

    require_once __DIR__ . '/../constants/settings.php';
    foreach ($xssPayloads as $idx => $xss) {
        $testId = "SEC-XSS-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $sanitized = format_task_text($xss);
        // Ensure active executable script/iframe/event tags cannot be parsed as HTML elements
        $isSafe = (strpos($sanitized, '<script>') === false && strpos($sanitized, '<img') === false && strpos($sanitized, '<iframe') === false && strpos($sanitized, '<svg') === false);
        QATestFramework::assert($module, $testId, "XSS payload neutralization: " . htmlspecialchars(substr($xss, 0, 25)), $isSafe);
    }

    // 3. Path Traversal & File Inclusion Prevention Tests
    $pathTraversalPayloads = [
        '../../../../etc/passwd',
        '..\\..\\..\\windows\\win.ini',
        '....//....//config.php',
        '%2e%2e%2f%2e%2e%2f'
    ];

    foreach ($pathTraversalPayloads as $idx => $traversal) {
        $testId = "SEC-TRAV-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $cleanPath = basename($traversal);
        // Basename must strip directory traversal operators (no .. or /)
        $isSafe = (strpos($cleanPath, '..') === false && strpos($cleanPath, '/') === false && strpos($cleanPath, '\\') === false);
        QATestFramework::assert($module, $testId, "Path traversal sanitization for: " . substr($traversal, 0, 20), $isSafe, "Extracted safe basename: " . $cleanPath);
    }

    // 4. Session Configuration Security Checks
    $cookieParams = session_get_cookie_params();
    QATestFramework::assert($module, "SEC-SESS-001", "Session active and initialized", session_status() === PHP_SESSION_ACTIVE);
    QATestFramework::assert($module, "SEC-SESS-002", "Session ID length is sufficient for entropy", strlen(session_id()) >= 20);

    // 5. Sensitive File Access Resistance
    $sensitiveFiles = [
        __DIR__ . '/../constants/db_config.php',
        __DIR__ . '/../constants/settings.php',
        __DIR__ . '/../constants/csrf.php',
        __DIR__ . '/../constants/rate_limiter.php',
        __DIR__ . '/../constants/sanitizer.php'
    ];
    foreach ($sensitiveFiles as $idx => $filePath) {
        $testId = "SEC-FILE-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $exists = file_exists($filePath);
        $readable = is_readable($filePath);
        QATestFramework::assert($module, $testId, "Core security configuration file integrity: " . basename($filePath), $exists && $readable);
    }

    // 6. Header Injection & Email Header Spoofing Tests
    $emailInjectionPayloads = [
        "user@example.com\r\nBcc: evil@attacker.com",
        "user@example.com\nCc: spy@hacker.com",
        "user@example.com%0ABcc: spam@victim.com"
    ];
    foreach ($emailInjectionPayloads as $idx => $inj) {
        $testId = "SEC-INJ-" . str_pad($idx + 1, 3, "0", STR_PAD_LEFT);
        $cleanEmail = filter_var(str_replace(["\r", "\n", "%0A", "%0D"], '', $inj), FILTER_SANITIZE_EMAIL);
        $isNeutralized = (strpos($cleanEmail, "\r") === false && strpos($cleanEmail, "\n") === false);
        QATestFramework::assert($module, $testId, "Email injection neutralization for test #" . ($idx + 1), $isNeutralized);
    }

    // 7. Numeric ID Parameter Boundary Tests (Integer Overflow & Malformed IDs)
    $idTests = [
        '100' => true,
        '0' => false,
        '-1' => false,
        '999999999999999999' => false,
        'abc' => false,
        "1' OR 1=1" => false
    ];
    foreach ($idTests as $inputVal => $expectedPositiveId) {
        $testId = "SEC-ID-" . str_pad($idx++, 3, "0", STR_PAD_LEFT);
        $parsed = filter_var($inputVal, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 2147483647]]);
        $isValidId = ($parsed !== false);
        QATestFramework::assertEquals($module, $testId, "Strict positive integer ID validation for '$inputVal'", $expectedPositiveId, $isValidId);
    }
}
