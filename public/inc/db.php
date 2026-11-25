<?php
// Conexión a PostgreSQL: Neon en Render (DATABASE_URL) o PostgreSQL local (XAMPP).
function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $url = getenv('DATABASE_URL');
    if ($url) {
        $p = parse_url($url);
        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s;sslmode=require',
            $p['host'], $p['port'] ?? 5432, ltrim($p['path'], '/'));
        $user = urldecode($p['user']); $pass = urldecode($p['pass']);
    } else {
        $dsn = sprintf('pgsql:host=%s;port=5432;dbname=%s',
            getenv('DB_HOST') ?: 'localhost', getenv('DB_NAME') ?: 'postgres');
        $user = getenv('DB_USER') ?: 'postgres';
        $pass = getenv('DB_PASS') ?: '';
    }
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}
