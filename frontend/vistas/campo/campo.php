<?php

require_once __DIR__ . '../../../../backend/controladores/campoController.php';

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['rol_id'])) {
  header('Location: ../../../index.php');
  exit;
}

$controller = new CampoController();
$mensaje = $controller->procesarFormularios();
$campos = $controller->obtenerCampos();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambosoft: Campos</title>
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
        <a href="campo.php" class="primerItem">Campos</a>
        <a href="../potrero/potrero.php">Potreros</a>
        <a href="#">Animales</a>
        <a href="#">Alimentos</a>
        <a href="../usuario/registrar.php">Usuarios</a></br></br></br></br>
        <a href="../usuario/cerrarSesion.php">Cerrar sesión</a>

      </nav>
      <!-- <label for="btn-menu">X</label> -->
    </div>
  </div>

  <!-- Seccion Registrar Campo -->

  <div class="wrapper seccionFormularios" id="seccionRegistrarCampo">
    <div class="title">Campos</div>
    <form method="POST">
      <div class="field">
        <input type="text" id="nombre" name="nombre" required>
        <label for="nombre">Nombre de campo</label>
      </div>
      <div class="field">
        <input type="text" id="ubicacion" name="ubicacion" required>
        <label for="ubicacion">Ubicación</label>
      </div>
      <div class="field">
        <div class="seccionBotones">
          <button type="submit" name="accion" value="registrar" class="botones">Registrar campo</button>
          <button type="submit" name="accion" value="modificar" class="botones">Modificar campo</button>
          <button type="submit" name="accion" value="eliminar" class="botones">Eliminar campo</button>
        </div>
      </div>
      <?php if ($mensaje): ?>
        <div style="margin-top: 30px;"><?= htmlspecialchars($mensaje) ?></div>
      <?php endif; ?>
    </form>
  </div>

  <h2 class="titulosSecciones">Campos</h2>
  <table class="tabla">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Ubicacion</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($campos)): ?>
        <?php foreach ($campos as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c->getNombre()) ?></td>
            <td><?= htmlspecialchars($c->getUbicacion()) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="3">No hay campos cargados.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>


</body>

</html>