<?php
/**
 * TaskBuddy QA Test Framework
 * Lightweight automated testing harness with assertion helpers, HTTP/DB simulation, and reporting.
 */

class QATestFramework {
    public static $totalTests = 0;
    public static $passedTests = 0;
    public static $failedTests = 0;
    public static $skippedTests = 0;
    public static $testResults = [];
    public static $startTime = 0;

    public static function init() {
        self::$startTime = microtime(true);
        self::$totalTests = 0;
        self::$passedTests = 0;
        self::$failedTests = 0;
        self::$skippedTests = 0;
        self::$testResults = [];
    }

    public static function assert($module, $testId, $description, $condition, $details = '') {
        self::$totalTests++;
        $status = $condition ? 'PASS' : 'FAIL';
        if ($condition) {
            self::$passedTests++;
        } else {
            self::$failedTests++;
        }

        self::$testResults[] = [
            'module' => $module,
            'id' => $testId,
            'description' => $description,
            'status' => $status,
            'details' => $details,
            'time' => round(microtime(true) - self::$startTime, 4)
        ];

        return $condition;
    }

    public static function assertEquals($module, $testId, $description, $expected, $actual, $details = '') {
        $condition = ($expected === $actual);
        $fullDetails = $condition ? $details : "Expected: " . var_export($expected, true) . " | Actual: " . var_export($actual, true) . ($details ? " | $details" : "");
        return self::assert($module, $testId, $description, $condition, $fullDetails);
    }

    public static function assertContains($module, $testId, $description, $needle, $haystack, $details = '') {
        $condition = (strpos((string)$haystack, (string)$needle) !== false);
        $fullDetails = $condition ? $details : "Needle '$needle' not found in target text. " . ($details ? " | $details" : "");
        return self::assert($module, $testId, $description, $condition, $fullDetails);
    }

    public static function assertNotEmpty($module, $testId, $description, $value, $details = '') {
        $condition = !empty($value);
        return self::assert($module, $testId, $description, $condition, $details ?: "Value is unexpectedly empty");
    }

    public static function getSummary() {
        $duration = round(microtime(true) - self::$startTime, 2);
        $passRate = (self::$totalTests > 0) ? round((self::$passedTests / self::$totalTests) * 100, 1) : 0;
        return [
            'total' => self::$totalTests,
            'passed' => self::$passedTests,
            'failed' => self::$failedTests,
            'skipped' => self::$skippedTests,
            'pass_rate' => $passRate,
            'duration_seconds' => $duration,
            'results' => self::$testResults
        ];
    }
}
