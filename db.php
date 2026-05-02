<?php
/**
 * db.php — Database connection (PDO)
 * Include this file in every page that needs DB access.
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // change to your MySQL user
define('DB_PASS', '');              // change to your MySQL password
define('DB_NAME', 'tradepro_db');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    // In production, log this error — never expose it to the user
    die(json_encode(['error' => 'Database connection failed.']));
}
