<?php
session_start();
require __DIR__ . '/inc/layout.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acepto'])) {
        $_SESSION['consentimiento'] = true;
        header('Location: registro.php'); exit;
    }
    $error = 'Para continuar debes aceptar el consentimiento informado.';
}
cabecera('Consentimiento informado'); ?>
<h1 class="h2">Consentimiento informado</h1>
<p><strong>Estudio:</strong> Diagnóstico de la capacidad de los usuarios para identificar contenido generado por inteligencia artificial mediante una plataforma web de evaluación.</p>
<p><strong>Responsables:</strong> Cristian Mañay y Francisco Carrión, estudiantes de la Universidad de las Fuerzas Armadas ESPE (proyecto académico de Lectura y Escritura de Textos Académicos).</p>
<ul>
  <li><strong>Qué harás:</strong> observarás o escucharás 20 ítems y decidirás si son reales o generados con IA, indicando qué tan seguro estás de cada respuesta.</li>
  <li><strong>Participación voluntaria:</strong> puedes abandonar el test en cualquier momento.</li>
  <li><strong>Anonimato:</strong> no se piden nombre, correo ni cédula. Se te asigna un código anónimo y se guardan tu edad, nivel educativo, familiaridad con la IA y tus respuestas.</li>
  <li><strong>Uso de los datos:</strong> solo con fines académicos y de investigación, de forma agrupada.</li>
  <li><strong>Riesgos:</strong> no hay riesgos conocidos más allá del tiempo que dediques al test.</li>
</ul>
<?php if ($error): ?><div class="alert alert-danger" role="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post">
  <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="acepto" id="acepto" required>
    <label class="form-check-label" for="acepto">Soy mayor de edad, he leído esta información y acepto participar de forma voluntaria.</label>
  </div>
  <button class="btn btn-primary" type="submit">Continuar</button>
  <a class="btn btn-outline-dark" href="index.php">Volver</a>
</form>
<?php pie();
