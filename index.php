<?php

session_start();
require_once __DIR__ . '../backend/controladores/usuarioController.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $controller = new UsuarioController();
  $usuario = $controller->loginUsuario($_POST['username'], $_POST['password']);

  if ($usuario) {
    $_SESSION['username'] = $usuario->getUsername();
    $_SESSION['rol_id'] = $usuario->getRol_id();
    $_SESSION['token'] = $usuario->getToken();

    if ($usuario->getRol_id() == 1) {
      header('Location: frontend/vistas/usuario/adminHome.php');
    } else {
      header('Location: frontend/vistas/usuario/usuarioHome.php');
    }
    exit;
  } else {
    $mensaje = "Usuario o contraseña incorrectos.";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambosoft: Iniciar sesión</title>
  <link rel="icon" href="frontend/img/logo2.png" type="image/png">
  <link rel="stylesheet" href="frontend/css/estilos.css" />
</head>

<body>
  <div class="contenido row justify-content-center">
    <div class="row w-75 contenedor">
      <img src="frontend/img/logo2.png" alt="Icono Tambosoft" id="logo">
    </div>
    <div class="wrapper divCentral">
      <div class="title">Inicia sesion</div>
      <form method="POST">
        <div class="field">
          <input type="text" required name="username" id="username">
          <label>Usuario</label>
        </div>
        <div class="field">
          <input type="password" required name="password" autocomplete="off" id="password">
          <label>Contraseña</label>
        </div>
        <!-- <div class="content">
                <div class="checkbox">
                    <input type="checkbox" id="remember-me">
                    <label for="remember-me">Recordar</label>
                </div>
                <div class="pass-link"><a href="#">Olvide mi contraseña</a></div>
            </div> -->
        <br>
        <div class="field">
          <button type="submit" class="botonIniciar">Iniciar sesión</button>
        </div>
        <?php if (!empty($mensaje)) echo "<p>$mensaje</p>"; ?>
      </form>
    </div>
  </div>
</body>

</html>