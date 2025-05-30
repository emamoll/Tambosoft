<?php

require_once __DIR__ . '../../DAOS/potreroDAO.php';
require_once __DIR__ . '../../DAOS/campoDAO.php';
require_once __DIR__ . '../../modelos/campo/campoModelo.php';

class PotreroController
{
  private $potreroDAO;

  public function __construct()
  {
    $this->potreroDAO = new PotreroDAO();
  }

  public function procesarFormularios()
  {
    $mensaje = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $accion = $_POST['accion'] ?? '';
      $nombre = trim($_POST['nombre'] ?? '');
      $superficie = trim($_POST['superficie'] ?? '');
      $pastura = trim($_POST['pastura'] ?? '');
      $campo_nombre = trim($_POST['campo_nombre'] ?? '');

      switch ($accion) {
        case 'registrar':
          if (empty($nombre) || empty($superficie) || empty($pastura) || empty($campo_nombre)) {
            return ['tipo' => 'error', 'mensaje' => 'Por favor, completá todos los campos para registrar.'];
          }

          if (!is_numeric($superficie) || $superficie <= 0) {
            return ['tipo' => 'error', 'mensaje' => 'La superficie debe ser un número positivo.'];
          }

          $campoDAO = new CampoDAO();
          $campo = $campoDAO->getCampoByNombre($campo_nombre);

          if (!$campo) {
            return ['tipo' => 'error', 'mensaje' => 'El campo seleccionado no existe.'];
          }

          $campo_id = $campo->getId();
          $potrero = new Potrero(null, $nombre, $superficie, $pastura, $campo_id);

          if ($this->potreroDAO->registrarPotrero($potrero)) {
            return ['tipo' => 'success', 'mensaje' => 'Potrero registrado correctamente'];
          } else {
            return ['tipo' => 'error', 'mensaje' => 'Error: ya existe un potrero con ese nombre'];
          }
        case 'modificar':
          if (empty($nombre)) {
            return ['tipo' => 'error', 'mensaje' => 'Por favor, ingresá el nombre del potrero que querés modificar.'];
          }

          $potreroActual = $this->potreroDAO->getPotreroByNombre($nombre);
          if (!$potreroActual) {
            return ['tipo' => 'error', 'mensaje' => 'Potrero no existe para modificar'];
          }

          $superficieNueva = $superficie !== '' ? $superficie : $potreroActual->getSuperficie();
          $pasturaNueva = $pastura !== '' ? $pastura : $potreroActual->getPastura();

          if (!empty($campo_nombre)) {
            $campoDAO = new CampoDAO();
            $campo = $campoDAO->getCampoByNombre($campo_nombre);
            if (!$campo) {
              return ['tipo' => 'error', 'mensaje' => 'Campo no existe'];
            }
            $campo_id_nuevo = $campo->getId();
          } else {
            $campo_id_nuevo = $potreroActual->getCampo_id();
          }

          $potreroModificado = new Potrero(null, $nombre, $superficie, $pastura, $campo_id_nuevo);

          if ($this->potreroDAO->modificarPotrero($potreroModificado)) {
            return ['tipo' => 'success', 'mensaje' => 'Potrero modificado correctamente'];
          } else {
            return ['tipo' => 'error', 'mensaje' => 'Error al modificar el potrero'];
          }
        case 'eliminar':
          if (empty($nombre)) {
            return ['tipo' => 'error', 'mensaje' => 'Por favor, ingresá el nombre del potrero que querés eliminar.'];
          }

          if ($this->potreroDAO->eliminarPotrero($nombre)) {
            return ['tipo' => 'success', 'mensaje' => 'Potrero eliminado correctamente'];
          } else {
            return ['tipo' => 'error', 'mensaje' => 'Error al eliminar el potrero'];
          }
        default:
          return ['tipo' => 'error', 'mensaje' => 'Acción no válida.'];
      }
    }
    return null;
  }

  public function obtenerPotreros()
  {
    return $this->potreroDAO->getAllPotreros();
  }
}
