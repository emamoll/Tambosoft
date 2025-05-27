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

      $campoDAO = new CampoDAO();
      $campo = $campoDAO->getCampoByNombre($campo_nombre);

      if (!$campo) {
        return "Error: el campo no existe.";
      }

      $campo_id = $campo->getId();

      $potrero = new Potrero(null, $nombre, $superficie, $pastura, $campo_id);

      switch ($accion) {
        case 'registrar':
          if ($this->potreroDAO->registrarPotrero($potrero)) {
            $mensaje = "Potrero registrado correctamente";
          } else {
            $mensaje = "Error: ya existe un potrero con ese nombre";
          }
          break;
        case 'modificar':
          if ($this->potreroDAO->modificarPotrero($potrero)) {
            $mensaje = "Potrero modificado correctamente";
          } else {
            $mensaje = "Error al modificar el potrero";
          }
          break;
        case 'eliminar':
          if ($this->potreroDAO->eliminarPotrero($nombre)) {
            $mensaje = "Potrero eliminado correctamente";
          } else {
            $mensaje = "Error al eliminar el potrero";
          }
          break;
      }
    }
    return $mensaje;
  }

  public function obtenerPotreros()
  {
    return $this->potreroDAO->getAllPotreros();
  }
}
