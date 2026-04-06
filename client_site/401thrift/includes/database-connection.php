<?php

$type = 'mysql';
$server = '192.185.2.183';
$db = 'angelben_clientSite';
$port = '3306';
$charset = 'utf8mb4';

$username = 'angelben_admin';
$password = 'A445a445.123.525';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

function thriftDbConfig(): array
{
    global $type, $server, $db, $port, $charset, $username, $password;

    return [
        'type' => $type,
        'host' => $server,
        'database' => $db,
        'port' => $port,
        'charset' => $charset,
        'username' => $username,
        'password' => $password,
    ];
}

function thriftDbConfigured(): bool
{
    $config = thriftDbConfig();

    foreach (['type', 'host', 'database', 'port', 'charset', 'username', 'password'] as $key) {
        if (!isset($config[$key]) || trim((string) $config[$key]) === '') {
            return false;
        }
    }

    return true;
}

function thriftDb(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if (!thriftDbConfigured()) {
        throw new RuntimeException('Database connection settings are incomplete in includes/database-connection.php.');
    }

    global $options;

    $config = thriftDbConfig();
    $dsn = sprintf(
        '%s:host=%s;dbname=%s;port=%s;charset=%s',
        $config['type'],
        $config['host'],
        $config['database'],
        $config['port'],
        $config['charset']
    );

    try {
        $pdo = new PDO($dsn, $config['username'], $config['password'], $options);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int) $e->getCode());
    }

    return $pdo;
}

function pdo(PDO $pdo, string $sql, ?array $arguments = null): PDOStatement
{
    if (!$arguments) {
        return $pdo->query($sql);
    }

    $statement = $pdo->prepare($sql);
    $statement->execute($arguments);
    return $statement;
}
