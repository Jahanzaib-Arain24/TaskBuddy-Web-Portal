<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "job_portal";

// Define dedicated constant keys to avoid variable collision with form POST $password
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'job_portal');

if (!function_exists('get_db_connection')) {
    /**
     * Singleton PDO connection helper to prevent duplicate database connections.
     * Reuses existing connection instance within the same request.
     * 
     * @return PDO
     */
    function get_db_connection() {
        static $db_conn = null;
        global $servername, $username, $password, $dbname;
        
        $host = !empty($servername) ? $servername : DB_HOST;
        $user = !empty($username) ? $username : DB_USER;
        $pass = (isset($password) && $password === "") ? "" : (defined('DB_PASS') ? DB_PASS : "");
        $name = !empty($dbname) ? $dbname : DB_NAME;
        
        if ($db_conn === null) {
            try {
                $db_conn = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                // Try fallback with empty password if first attempt failed
                try {
                    $db_conn = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, "", [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);
                } catch (PDOException $e2) {
                    error_log("Database connection failed in " . __FILE__ . ":" . __LINE__ . " - " . $e2->getMessage());
                    throw $e2;
                }
            }
        }
        return $db_conn;
    }
}
?>