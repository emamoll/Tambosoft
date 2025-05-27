<?php

session_start();
require_once __DIR__ . '../../../../backend/controladores/usuarioController.php';

if (!isset($_SESSION['token'])) {
  header("Location: index.php");
  exit;
}

$controller = new UsuarioController();
$usuario = $controller->getUsuarioPorToken($_SESSION['token']);

if (!$usuario) {
  session_destroy();
  header("Location: index.php");
  exit;
}

if ($usuario->getRol_id() != 1) {
  header('Location: usuario.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambosoft: Home</title>
  <link rel="icon" href=".../../../../img/logo2.png" type="image/png">
  <link rel="stylesheet" href="../../css/estilos.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body class="bodyHome">
  <header class="bordeH">
    <div class="btn-menu">
      <label for="btn-menu" class="icon-menu">☰</label>
    </div>
    <a href="adminHome.php" class="logoIndex"><img src="../../img/logoChico.png" alt="Logo Tambosoft" class="logo" /></a>
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
        <a href="../potrero/potrero.php">Potreros</a>
        <a href="#">Animales</a>
        <a href="#">Alimentos</a>
        <a href="../usuario/registrar.php">Usuarios</a></br></br></br></br>
        <a href="cerrarSesion.php">Cerrar sesión</a>

      </nav>
      <!-- <label for="btn-menu">X</label> -->
    </div>
  </div>

  <h1 class="mensajeBienvenida">Bienvenido <?php echo $usuario->getUsername() ?></h1>
</body>

</html>