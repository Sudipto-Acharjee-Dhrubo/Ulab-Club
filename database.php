<?php
require_once __DIR__ . '/config.php';

function db() : PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
    }
    return $pdo;
}

function ensure_session_started() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function is_logged_in() : bool {
    ensure_session_started();
    return isset($_SESSION['student_id']);
}

function current_student_id() {
    ensure_session_started();
    return $_SESSION['student_id'] ?? null;
}

function redirect(string $path) {
    header('Location: ' . BASE_URL . $path);
    exit;
}
?>
