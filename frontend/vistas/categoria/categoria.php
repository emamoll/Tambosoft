<?php

require_once __DIR__ . '../../../../backend/controladores/categoriaController.php';
require_once __DIR__ . '../../../../backend/controladores/potreroController.php';

session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['rol_id'])) {
	header('Location: ../../../index.php');
	exit;
}

$controllerCategoria = new CategoriaController();
$categorias = $controllerCategoria->obtenerCategorias();
$mensaje = $controllerCategoria->procesarFormulario();
$controllerPotrero = new PotreroController();
$potreros = $controllerPotrero->obtenerPotreros();

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tambosoft: Categorias</title>
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
			<div class="form-title">Categorias</div>
			<form method="POST">
				<div class="form-group">
					<input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
						placeholder=" ">
					<label for="nombre">Nombre de la categoria</label>
				</div>
				<div class="form-group select-group">
					<?php
					$potrerosDisponibles = array_filter($potreros, function ($p) use ($categorias) {
						foreach ($categorias as $c) {
							if ($c->getPotrero_id() === $p->getId()) {
								return false;
							}
						}
						return true;
					});
					?>
					<select name="potrero_nombre">
						<option value="" disabled <?= empty($_POST['potrero_nombre']) ? 'selected' : '' ?>>Seleccione un potrero
						</option>
						<?php foreach ($potrerosDisponibles as $p): ?>
						<option value="<?= htmlspecialchars($p->getNombre()) ?>">
							<?= htmlspecialchars($p->getNombre()) ?>
						</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="botones-container">
					<button type="submit" name="accion" value="registrar">Registrar categoria</button>
					<button type="submit" name="accion" value="modificar">Modificar categoria</button>
					<button type="submit" name="accion" value="eliminar">Eliminar categoria</button>
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

		<h2 class="titulosSecciones">Categorias</h2>
		<table class="tabla" id="tablaContainerPo">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Potrero</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($categorias)): ?>
				<?php foreach ($categorias as $c): ?>
				<tr>
					<td>
						<?= htmlspecialchars($c->getNombre()) ?>
					</td>
					<td>
						<?php
						foreach ($potreros as $p) {
							if ($p->getId() === $c->getPotrero_id()) {
								echo htmlspecialchars($p->getNombre());
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