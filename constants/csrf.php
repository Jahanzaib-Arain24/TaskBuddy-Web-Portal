<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate or retrieve CSRF token from current session.
 * 
 * @return string
 */
function get_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Alias for get_csrf_token()
 */
function generate_csrf_token() {
    return get_csrf_token();
}

/**
 * Output hidden CSRF HTML input tag.
 * 
 * @return string
 */
function csrf_field() {
    $token = get_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Validate incoming CSRF token against session token.
 * 
 * @param string|null $token
 * @return bool
 */
function validate_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}
?>
