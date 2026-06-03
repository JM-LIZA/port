<?php
// ============================================================
//  config.php  –  Database connection + global settings
//  Edit DB_USER / DB_PASS / DB_NAME to match your setup
// ============================================================

define('DB_HOST',    'localhost');
define('DB_USER',    'root');       // ← change to your MySQL username
define('DB_PASS',    '');           // ← change to your MySQL password
define('DB_NAME',    'ee_portfolio');
define('DB_CHARSET', 'utf8mb4');

define('SITE_TITLE', 'Alex Reyes — Electrical Engineer');
define('SITE_OWNER', 'Alex Reyes');

// ── PDO connection (singleton-style) ──────────────────────────
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:monospace;padding:2rem;color:#c00">
                 DB Connection Error: ' . htmlspecialchars($e->getMessage()) . '
                 <br>Check config.php credentials.</div>');
        }
    }
    return $pdo;
}