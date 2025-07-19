<?php

require_once __DIR__ . "../../../../backend/controladores/alimentoController.php";
require_once __DIR__ . "../../../../backend/controladores/campoController.php";
require_once __DIR__ . "../../../../backend/controladores/almacenController.php";

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['rol_id'])) {
  header('Location: ../../../index.php');
  exit;
}

$controllerAlmacen = new AlmacenController();
$almacenes = $controllerAlmacen->obtenerAlmacenes();
$mensaje = $controllerAlmacen->procesarFormularios();
$controllerAlimento = new AlimentoController();
$alimentos = $controllerAlimento->obtenerAlimentos();
$controllerCampo = new CampoController();
$campos = $controllerCampo->obtenerCampos();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambosoft: Stocks</title>
  <link rel="icon" href=".../../../../img/logo2.png" type="image/png">
  <link rel="stylesheet" href="../../css/estilos.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bodyHome">
  <?php require_once __DIR__ . '../../secciones/header.php'; ?>
  <!--	--------------->
  <?php require_once __DIR__ . '../../secciones/navbar.php'; ?>
  <div class="main">
    <div class="form-container" id="formCampoContainer">
      <div class="form-title">Stocks</div>
      <form method="POST">
        <div class="form-group">
          <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
            placeholder=" ">
          <label for="nombre">Nombre del almacen</label>
        </div>
        <div class="form-group select-group">
          <select name="alimento_nombre">
            <option value="" disabled <?= empty($_POST['alimento_nombre']) ? 'selected' : '' ?>>Seleccione un
              alimento
            </option>
            <?php foreach ($alimentos as $a): ?>
            <option value="<?= htmlspecialchars($a->getNombre()) ?>" <?= (isset($_POST['alimento_nombre']) && $_POST['alimento_nombre'] === $a->getNombre()) ? 'selected' : '' ?>>
              <?= htmlspecialchars($a->getNombre()) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>"
            placeholder=" ">
          <label for="stock">Stock</label>
        </div>
        <div class="form-group select-group">
          <select name="campo_nombre">
            <option value="" disabled <?= empty($_POST['campo_nombre']) ? 'selected' : '' ?>>Seleccione un
              campo
            </option>
            <?php foreach ($campos as $c): ?>
            <option value="<?= htmlspecialchars($c->getNombre()) ?>" <?= (isset($_POST['campo_nombre']) && $_POST['campo_nombre'] === $c->getNombre()) ? 'selected' : '' ?>>
              <?= htmlspecialchars($c->getNombre()) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>







        <!-- Validacion para que haya un almacen por campo, por ahora no se usa -->
        <!--<div class="form-group select-group">
           <?php
           $camposDisponibles = array_filter($campos, function ($c) use ($almacenes) {
             foreach ($almacenes as $a) {
               if ($a->getCampo_id() === $c->getId()) {
                 return false;
               }
             }
             return true;
           });
           ?> 
          <select name="campo_nombre">
            <option value="" disabled <?= empty($_POST['campo_nombre']) ? 'selected' : '' ?>>Seleccione un
              campo
            </option>
            <?php foreach ($camposDisponibles as $c): ?>
            <option value="<?= htmlspecialchars($c->getNombre()) ?>">
              <?= htmlspecialchars($c->getNombre()) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>-->
        <div class="botones-container">
          <button type="submit" name="accion" value="registrar">Registrar stock</button>
          <button type="submit" name="accion" value="modificar">Modificar stock</button>
          <button type="submit" name="accion" value="eliminar">Eliminar stock</button>
        </div>
        <?php if (!empty($mensaje)): ?>
          <script>
            Swal.fire({
              icon: '<?= $mensaje["tipo"] ?>',
              title: '<?= $mensaje["tipo"] === "success" ? "Éxito" : "Atención" ?>',
              text: <?= json_encode($mensaje["mensaje"]) ?>,
              confirmButtonColor: '#3085d6'
            }).then(() => {
              <?php if ($mensaje["tipo"] === "success"): ?>
                window.location.href = window.location.pathname; // recargar sin reenviar POST
              <?php endif; ?>
            });
          </script>
        <?php endif; ?>
      </form>
    </div>
    <h2 class="titulosSecciones">Almacenes</h2>
    <table class="tabla" id="tablaContainerPo">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Alimento</th>
          <th>Stock</th>
          <th>Campo</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($almacenes)): ?>
          <?php foreach ($almacenes as $a): ?>
            <tr>
              <td>
                <?= htmlspecialchars($a->getNombre()) ?>
              </td>
              <td>
                <?php
                foreach ($alimentos as $ali) {
                  if ($ali->getId() === $a->getAlimento_id()) {
                    echo htmlspecialchars($ali->getNombre());
                    break;
                  }
                }
                ?>
              </td>
              <td>
                <?= htmlspecialchars($a->getStock()) ?>
              </td>
              <td>
                <?php
                foreach ($campos as $c) {
                  if ($c->getId() === $a->getCampo_id()) {
                    echo htmlspecialchars($c->getNombre());
                    break;
                  }
                }
                ?>
              </td>
            <?php endforeach; ?>
          <?php else: ?>
          <tr>
            <td colspan="3">No hay categorias cargadas.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</body>

</html>