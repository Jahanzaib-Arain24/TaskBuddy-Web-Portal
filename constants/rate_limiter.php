<?php
/**
 * Rate Limiter for TaskBuddy Application
 * Prevents brute-force attacks and abuse on sensitive endpoints (login, register, password reset).
 */

if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

if (!function_exists('get_client_ip')) {
    function get_client_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return '127.0.0.1';
    }
}

/**
 * Check if the current action is rate-limited.
 *
 * @param string $action Action key identifier (e.g., 'user_login', 'admin_login', 'register')
 * @param int $max_attempts Maximum allowed failed attempts
 * @param int $decay_seconds Time window in seconds (default: 15 minutes = 900 seconds)
 * @return bool True if allowed, false if locked out/rate-limited
 */
function check_rate_limit($action, $max_attempts = 5, $decay_seconds = 900) {
    $ip = get_client_ip();
    $key = 'rl_' . md5($action . '_' . $ip);
    $now = time();

    if (!isset($_SESSION[$key])) {
        return true;
    }

    $record = $_SESSION[$key];

    // Check if decay window expired
    if ($now - $record['first_attempt'] > $decay_seconds) {
        unset($_SESSION[$key]);
        return true;
    }

    if ($record['attempts'] >= $max_attempts) {
        return false;
    }

    return true;
}

/**
 * Record a failed attempt for the specified action.
 *
 * @param string $action
 * @param int $decay_seconds
 */
function record_rate_limit_attempt($action, $decay_seconds = 900) {
    $ip = get_client_ip();
    $key = 'rl_' . md5($action . '_' . $ip);
    $now = time();

    if (!isset($_SESSION[$key]) || ($now - $_SESSION[$key]['first_attempt'] > $decay_seconds)) {
        $_SESSION[$key] = [
            'attempts' => 1,
            'first_attempt' => $now,
            'last_attempt' => $now
        ];
    } else {
        $_SESSION[$key]['attempts']++;
        $_SESSION[$key]['last_attempt'] = $now;
    }
}

/**
 * Reset/clear rate limit upon successful action.
 *
 * @param string $action
 */
function reset_rate_limit($action) {
    $ip = get_client_ip();
    $key = 'rl_' . md5($action . '_' . $ip);
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

/**
 * Alias for reset_rate_limit
 */
function clear_rate_limit($action) {
    reset_rate_limit($action);
}

/**
 * Get remaining lockout time in seconds.
 *
 * @param string $action
 * @param int $decay_seconds
 * @return int Remaining seconds (0 if not locked)
 */
function get_rate_limit_remaining_seconds($action, $decay_seconds = 900) {
    $ip = get_client_ip();
    $key = 'rl_' . md5($action . '_' . $ip);
    $now = time();

    if (!isset($_SESSION[$key])) {
        return 0;
    }

    $elapsed = $now - $_SESSION[$key]['first_attempt'];
    if ($elapsed >= $decay_seconds) {
        unset($_SESSION[$key]);
        return 0;
    }

    return ($decay_seconds - $elapsed);
}
?>
