<?php
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function cabecera(string $titulo): void { ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo) ?> · Detección de contenido generado con IA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/estilo.css" rel="stylesheet">
</head>
<body>
  <a class="salto" href="#contenido">Saltar al contenido</a>
  <header class="barra"><div class="container"><strong>Detección de contenido generado con IA</strong></div></header>
  <main id="contenido" class="container py-4" tabindex="-1">
<?php }
function pie(): void { ?>
  </main>
  <footer class="container py-3 pie">Grupo 6 · Universidad de las Fuerzas Armadas ESPE · Lectura y Escritura de Textos Académicos</footer>
</body>
</html>
<?php }
