<?php

require_once __DIR__ . '../../DAOS/campoDAO.php';
require_once __DIR__ . '../../modelos/campo/campoModelo.php';

class CampoController
{
  private $campoDAO;

  public function __construct()
  {
    $this->campoDAO = new CampoDAO();
  }

  public function procesarFormularios()
  {
    $mensaje = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $accion = $_POST['accion'] ?? '';
      $nombre = $_POST['nombre'] ?? '';
      $ubicacion = $_POST['ubicacion'] ?? '';

      $campo = new Campo(null, $nombre, $ubicacion);

      switch ($accion) {
        case 'registrar':
          if ($this->campoDAO->registrarCampo($campo)) {
            $mensaje = "Campo registrado correctamente";
          } else {
            $mensaje = "Error: ya existe un campo con ese nombre";
          }
          break;
        case 'modificar':
          if ($this->campoDAO->modificarCampo($campo)) {
            $mensaje = "Campo modificado correctamente";
          } else {
            $mensaje = "Error al modificar el campo";
          }
          break;
        case 'eliminar':
          if ($this->campoDAO->eliminarCampo($nombre)) {
            $mensaje = "Campo eliminado correctamente";
          } else {
            $mensaje = "Error al eliminar el campo";
          }
          break;
      }
    }
    return $mensaje;
  }

  public function obtenerCampos()
  {
    return $this->campoDAO->getAllCampos();
  }
}
