<?php
session_start();
require __DIR__ . '/inc/layout.php';
require __DIR__ . '/inc/db.php';
if (empty($_SESSION['consentimiento'])) { header('Location: consentimiento.php'); exit; }

$niveles = ['Bachillerato', 'Tercer nivel (grado)', 'Cuarto nivel (posgrado)'];
$errores = []; $codigo = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $edad  = filter_var($_POST['edad'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 18, 'max_range' => 99]]);
    $nivel = $_POST['nivel'] ?? '';
    $fam   = filter_var($_POST['familiaridad'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]]);
    if ($edad === false)              $errores[] = 'Indica una edad válida (mayor de 18 años).';
    if (!in_array($nivel, $niveles, true)) $errores[] = 'Selecciona tu nivel educativo.';
    if ($fam === false)               $errores[] = 'Indica tu familiaridad con la IA.';
    if (!$errores) {
        try {
            $pdo = db();
            for ($i = 0; $i < 5 && !$codigo; $i++) {
                $c = 'P' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
                $st = $pdo->prepare('INSERT INTO participantes (codigo, edad, nivel_educativo, familiaridad_ia)
                                     VALUES (?, ?, ?, ?) ON CONFLICT (codigo) DO NOTHING RETURNING id');
                $st->execute([$c, $edad, $nivel, $fam]);
                if ($id = $st->fetchColumn()) { $codigo = $c; $_SESSION['participante_id'] = $id; $_SESSION['codigo'] = $c; }
            }
            if (!$codigo) $errores[] = 'No se pudo generar el código. Intenta de nuevo.';
        } catch (Throwable $ex) {
            error_log($ex->getMessage());
            $errores[] = 'No se pudo guardar el registro en este momento. Intenta de nuevo en unos minutos.';
        }
    }
}
cabecera('Registro'); ?>
<?php if ($codigo): ?>
  <h1 class="h2">Registro completado</h1>
  <div class="alert alert-success" role="status">Tu código anónimo es <strong><?= e($codigo) ?></strong>. Anótalo por si lo necesitas.</div>
  <p>El test de 20 ítems se activa en la siguiente fase del desarrollo.</p>
<?php else: ?>
  <h1 class="h2">Registro del participante</h1>
  <p>No pedimos tu nombre ni tu correo. Se te asignará un código anónimo.</p>
  <?php if ($errores): ?><div class="alert alert-danger" role="alert"><ul class="mb-0"><?php foreach ($errores as $m): ?><li><?= e($m) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
  <form method="post" novalidate>
    <div class="mb-3">
      <label class="form-label" for="edad">Edad (años)</label>
      <input class="form-control" type="number" id="edad" name="edad" min="18" max="99" inputmode="numeric" required value="<?= e($_POST['edad'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label" for="nivel">Nivel educativo</label>
      <select class="form-select" id="nivel" name="nivel" required>
        <option value="">Selecciona una opción</option>
        <?php foreach ($niveles as $n): ?><option <?= (($_POST['nivel'] ?? '') === $n) ? 'selected' : '' ?>><?= e($n) ?></option><?php endforeach; ?>
      </select>
    </div>
    <fieldset class="mb-3">
      <legend class="form-label fs-6">¿Qué tan familiarizado estás con la inteligencia artificial?</legend>
      <?php $f = ['1' => '1 · Nada', '2' => '2 · Poco', '3' => '3 · Algo', '4' => '4 · Bastante', '5' => '5 · Mucho'];
      foreach ($f as $v => $t): ?>
        <div class="form-check"><input class="form-check-input" type="radio" name="familiaridad" id="f<?= $v ?>" value="<?= $v ?>" <?= (($_POST['familiaridad'] ?? '') === (string)$v) ? 'checked' : '' ?>>
        <label class="form-check-label" for="f<?= $v ?>"><?= e($t) ?></label></div>
      <?php endforeach; ?>
    </fieldset>
    <button class="btn btn-primary" type="submit">Registrarme</button>
  </form>
<?php endif; ?>
<?php pie();
