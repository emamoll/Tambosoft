<?php

require_once __DIR__ . '../../../../backend/controladores/potreroController.php';
require_once __DIR__ . '../../../../backend/controladores/campoController.php';

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['rol_id'])) {
  header('Location: ../../../index.php');
  exit;
}

$controllerPotrero = new PotreroController();
$mensaje = $controllerPotrero->procesarFormularios();
$potreros = $controllerPotrero->obtenerPotreros();

$controllerCampo = new CampoController();
$campos = $controllerCampo->obtenerCampos();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambosoft: Potreros</title>
  <link rel="icon" href=".../../../../img/logo2.png" type="image/png">
  <link rel="stylesheet" href="../../css/estilos.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="campo.js"></script>
</head>

<body class="bodyHome">
  <header class="bordeH">
    <div class="btn-menu">
      <label for="btn-menu" class="icon-menu">☰</label>
    </div>
    <a href="../usuario/adminHome.php" class="logoIndex"><img src="../../img/logoChico.png" alt="Logo Tambosoft" class="logo" /></a>
    <nav>
      <ul class="flex">
      </ul>
    </nav>
  </header>
  <div class="capa"></div>
  <!--	--------------->
  <input type="checkbox" id="btn-menu" />
  <div class="container-menu">
    <div class="cont-menu">
      <nav>
        <a href="../campo/campo.php" class="primerItem">Campos</a>
        <a href="potrero.php">Potreros</a>
        <a href="#">Animales</a>
        <a href="#">Alimentos</a>
        <a href="../usuario/registrar.php">Usuarios</a></br></br></br></br>
        <a href="../usuario/cerrarSesion.php">Cerrar sesión</a>

      </nav>
      <!-- <label for="btn-menu">X</label> -->
    </div>
  </div>

  <div class="wrapper seccionFormularios" id="seccionRegistrarPotrero">
    <div class="title">Potreros</div>
    <form method="POST">
      <div class="field">
        <input type="text" id="nombre" name="nombre" required>
        <label for="nombre">Nombre de potrero</label>
      </div>
      <div class="field">
        <input type="text" id="superficie" name="superficie" required>
        <label for="superficie">Superficie</label>
      </div>
      <div class="field">
        <input type="text" id="pastura" name="pastura" required>
        <label for="pastura">Pastura</label>
      </div>
      <div class="field">
        <!-- <select name="campo_nombre" required>
          <?php
          foreach ($campos as $c) {
            echo "<option value='" . htmlspecialchars($c->getNombre()) . "'>" . htmlspecialchars($c->getNombre()) . "</option>";
          }
          ?>
        </select> -->
        <select name="campo_nombre" required>
          <option value="" disabled selected>Seleccione un campo</option>
          <?php foreach ($campos as $c): ?>
            <option value="<?= htmlspecialchars($c->getNombre()) ?>">
              <?= htmlspecialchars($c->getNombre()) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <div class="seccionBotones">
          <button type="submit" name="accion" value="registrar" class="botones">Registrar potrero</button>
          <button type="submit" name="accion" value="modificar" class="botones">Modificar potrero</button>
          <button type="submit" name="accion" value="eliminar" class="botones">Eliminar potrero</button>
        </div>
      </div>
      <?php if ($mensaje): ?>
        <div style="margin-top: 30px;"><?= htmlspecialchars($mensaje) ?></div>
      <?php endif; ?>
    </form>
  </div>

  <h2 class="seccionPotrero">Potreros</h2>
  <table class="tabla">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Superficie</th>
        <th>Pastura</th>
        <th>Campo</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($potreros)): ?>
        <?php foreach ($potreros as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p->getNombre()) ?></td>
            <td><?= htmlspecialchars($p->getSuperficie()) ?></td>
            <td><?= htmlspecialchars($p->getPastura()) ?></td>
            <td>
              <?php
              foreach ($campos as $c) {
                if ($c->getId() === $p->getCampo_id()) {
                  echo htmlspecialchars($c->getNombre());
                  break;
                }
              }
              ?>
            </td>

          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3">No hay potreros cargados.</td>
          </tr>
        <?php endif; ?>
    </tbody>
  </table>


</body>

</html>