<?php
/**
 * MySQL connection helpers for cPanel hosting.
 * Update db/config.php after deployment.
 */

function thriftDbConfig(): array {
    $configFile = dirname(__DIR__) . '/db/config.php';
    if (!file_exists($configFile)) {
        throw new RuntimeException('Missing db/config.php. Copy db/config.example.php to db/config.php and update your cPanel MySQL credentials.');
    }

    $config = require $configFile;
    if (!is_array($config)) {
        throw new RuntimeException('db/config.php must return a configuration array.');
    }

    return $config;
}

function thriftDbConfigured(): bool {
    $configFile = dirname(__DIR__) . '/db/config.php';
    if (!file_exists($configFile)) {
        return false;
    }

    $config = require $configFile;
    if (!is_array($config)) {
        return false;
    }

    $required = ['host', 'database', 'username', 'password', 'charset'];
    foreach ($required as $key) {
        if (!array_key_exists($key, $config) || trim((string)$config[$key]) === '') {
            return false;
        }
    }

    $placeholders = [
        'YOUR_CPANEL_MYSQL_HOST',
        'YOUR_CPANEL_DATABASE_NAME',
        'YOUR_CPANEL_DATABASE_USER',
        'YOUR_CPANEL_DATABASE_PASSWORD',
    ];

    foreach (['host', 'database', 'username', 'password'] as $key) {
        if (in_array((string)$config[$key], $placeholders, true)) {
            return false;
        }
    }

    return true;
}

function thriftDb(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if (!thriftDbConfigured()) {
        throw new RuntimeException('Database is not configured. Update db/config.php with your cPanel MySQL credentials and import db/schema.sql.');
    }

    $config = thriftDbConfig();
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['database'],
        $config['charset']
    );

    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}
