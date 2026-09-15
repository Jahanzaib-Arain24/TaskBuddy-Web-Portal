<?php
/**
 * Input Sanitization & Validation Helper for TaskBuddy Application
 * Protects against XSS, script injection, and invalid data formats.
 */

if (!function_exists('sanitize_input')) {
    /**
     * Clean and strip harmful tags/null bytes from general string input.
     *
     * @param mixed $data
     * @return string
     */
    function sanitize_input($data) {
        if (is_array($data)) {
            return array_map('sanitize_input', $data);
        }
        if ($data === null) {
            return '';
        }
        $data = trim((string)$data);
        $data = str_replace(chr(0), '', $data); // Strip null bytes
        $data = strip_tags($data); // Strip HTML tags
        return $data;
    }
}

if (!function_exists('sanitize_text')) {
    /**
     * Sanitize multi-line text (allowing basic formatting while stripping scripts).
     *
     * @param string $data
     * @return string
     */
    function sanitize_text($data) {
        if ($data === null) return '';
        $data = trim((string)$data);
        $data = str_replace(chr(0), '', $data);
        // Remove script and style tags completely along with their inner content
        $data = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $data);
        return strip_tags($data, '<p><br><strong><em><ul><ol><li><b><i>');
    }
}

if (!function_exists('validate_email_address')) {
    /**
     * Strictly validate email address format.
     *
     * @param string $email
     * @return string|false Normalized email or false if invalid
     */
    function validate_email_address($email) {
        $clean = trim((string)$email);
        if (filter_var($clean, FILTER_VALIDATE_EMAIL)) {
            return strtolower($clean);
        }
        return false;
    }
}

if (!function_exists('sanitize_alphanumeric')) {
    /**
     * Filter string to alphanumeric plus common punctuation (e.g. usernames, IDs).
     *
     * @param string $str
     * @param string $allow Extra allowed characters (default: '-_')
     * @return string
     */
    function sanitize_alphanumeric($str, $allow = '-_') {
        $escaped = preg_quote($allow, '/');
        return preg_replace('/[^a-zA-Z0-9' . $escaped . ']/', '', (string)$str);
    }
}

if (!function_exists('escape_html')) {
    /**
     * Securely escape HTML for output in templates.
     *
     * @param mixed $value
     * @return string
     */
    function escape_html($value) {
        if ($value === null) return '';
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
?>
