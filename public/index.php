<?php
// Página de prueba de publicación: GitHub -> Render -> Neon (PostgreSQL)
$estado = 'sin probar';
$detalle = '';
$ok = false;

try {
    $url = getenv('DATABASE_URL');
    if ($url) {
        // Neon entrega: postgresql://usuario:clave@host/base?sslmode=require
        $p = parse_url($url);
        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s;sslmode=require',
            $p['host'], $p['port'] ?? 5432, ltrim($p['path'], '/'));
        $user = urldecode($p['user']);
        $pass = urldecode($p['pass']);
        $origen = 'Neon (DATABASE_URL)';
    } else {
        // Prueba local con XAMPP y PostgreSQL
        $dsn = 'pgsql:host=localhost;port=5432;dbname=postgres';
        $user = 'postgres';
        $pass = getenv('DB_PASS') ?: 'admin';
        $origen = 'PostgreSQL local';
    }
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $fila = $pdo->query('SELECT now() AS hora, version() AS version')->fetch(PDO::FETCH_ASSOC);
    $ok = true;
    $estado = 'Conexión exitosa';
    $detalle = $origen . ' · ' . $fila['hora'] . ' · ' . $fila['version'];
} catch (Throwable $e) {
    $estado = 'Error de conexión';
    $detalle = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prueba de publicación</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h1 class="h3">Prueba de publicación · Grupo 6</h1>
  <p>PHP <?= htmlspecialchars(PHP_VERSION) ?> · extensión pdo_pgsql: <?= extension_loaded('pdo_pgsql') ? 'activa' : 'NO activa' ?></p>
  <div class="alert <?= $ok ? 'alert-success' : 'alert-danger' ?>">
    <strong><?= htmlspecialchars($estado) ?></strong><br>
    <small><?= htmlspecialchars($detalle) ?></small>
  </div>
</body>
</html>
