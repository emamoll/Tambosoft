<?php

require_once __DIR__ . "../../DAOS/almacenDAO.php";
require_once __DIR__ . "../../DAOS/alimentoDAO.php";
require_once __DIR__ . "../../DAOS/campoDAO.php";
require_once __DIR__ . "../../modelos/almacen/almacenModelo.php";

class AlmacenController
{
    private $almacenDAO;

    public function __construct()
    {
        $this->almacenDAO = new AlmacenDAO();
    }

    public function procesarFormularios()
    {
        $mensaje = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            $nombre = trim($_POST['nombre'] ?? '');
            $alimento_nombre = trim($_POST['alimento_nombre'] ?? '');
            $stock = trim($_POST['stock'] ?? '');
            $campo_nombre = trim($_POST['campo_nombre'] ?? '');

            switch ($accion) {
                case 'registrar':
                    if (empty($nombre) || empty($alimento_nombre) || empty($stock) || empty($campo_nombre)) {
                        return ['tipo' => 'error', 'mensaje' => 'Por favor, completá todos los campos para registrar.'];
                    }

                    if (!is_numeric($stock) || $stock <= 0) {
                        return ['tipo' => 'error', 'mensaje' => 'El stock debe ser un número positivo.'];
                    }

                    $alimentoDAO = new AlimentoDAO();
                    $alimento = $alimentoDAO->getAlimentoByNombre($alimento_nombre);
                    $alimento_id = $alimento->getId();

                    if (!$alimento) {
                        return ['tipo' => 'error', 'mensaje' => 'El alimento seleccionado no existe.'];
                    }

                    $campoDAO = new CampoDAO();
                    $campo = $campoDAO->getCampoByNombre($campo_nombre);

                    if (!$campo) {
                        return ['tipo' => 'error', 'mensaje' => 'El campo seleccionado no existe.'];
                    }

                    $campo_id = $campo->getId();
                    $almacenExistente = $this->almacenDAO->getAlmacenByCampoId($campo->getId());

                    // Validar que solamente haya un alamcen por campo
                    // if ($almacenExistente) {
                    //     return ['tipo' => 'error', 'mensaje' => 'Ese campo ya tiene un almacen asignado.'];
                    // }

                    // $almacenesExistentes = $this->almacenDAO->getAllAlmacenes();
                    // foreach ($almacenesExistentes as $aE) {
                    //     if ($aE->getNombre() === $nombre) {
                    //         return ['tipo' => 'error', 'mensaje' => 'Ya existe un almacen con ese nombre.'];
                    //     }
                    // }

                    $almacen = new Almacen(null, $nombre, $alimento_id, $stock, $campo_id);

                    if ($this->almacenDAO->registrarAlmacen($almacen)) {
                        return ['tipo' => 'success', 'mensaje' => 'Almacen registrado correctamente'];
                    } else {
                        return ['tipo' => 'error', 'mensaje' => 'Error: ya existe un almacen con ese nombre'];
                    }
                case 'modificar':
                    if (empty($nombre)) {
                        return ['tipo' => 'error', 'mensaje' => 'Por favor, ingresá el nombre de la categoría que querés modificar.'];
                    }

                    $almacenActual = $this->almacenDAO->getAlmacenByNombre($nombre);

                    if (!$almacenActual) {
                        return ['tipo' => 'error', 'mensaje' => 'El almacen no existe para modificar.'];
                    }

                    // Si el campo fue cambiado, validar que no esté ocupado por otro almacen
                    if (!empty($campo_nombre)) {
                        $campoDAO = new CampoDAO();
                        $campo = $campoDAO->getCampoByNombre($campo_nombre);
                        if (!$campo) {
                            return ['tipo' => 'error', 'mensaje' => 'El campo no existe.'];
                        }

                        // Validar que solo haya un almacen por campo
                        // $almacenConEseCampo = $this->almacenDAO->getAlmacenByCampoId($campo->getId());

                        // if ($almacenConEseCampo && $almacenConEseCampo['id'] !== $almacenActual->getId()) {
                        //     return ['tipo' => 'error', 'mensaje' => 'Ese campo ya tiene un almacen asignado.'];
                        // }

                        $campo_id_nuevo = $campo->getId();
                    } else {
                        $campo_id_nuevo = $almacenActual->getCampo_id();
                    }

                    if (!empty($alimento_nombre)) {
                        $alimentoDAO = new AlimentoDAO();
                        $alimento = $alimentoDAO->getAlimentoByNombre($alimento_nombre);
                        if (!$campo) {
                            return ['tipo' => 'error', 'mensaje' => 'El alimento no existe.'];
                        }
                        $alimento_id_nuevo = $alimento->getId();
                    } else {
                        $alimento_id_nuevo = $almacenActual->getAlimento_id();
                    }

                    $stockNuevo = $stock !== '' ? $stock : $almacenActual->getStock();

                    $almacenModificado = new Almacen(null, $nombre, $alimento_id_nuevo, $stockNuevo, $campo_id_nuevo);

                    if ($this->almacenDAO->modificarAlmacen($almacenModificado)) {
                        return ['tipo' => 'success', 'mensaje' => 'Almacen modificado correctamente'];
                    } else {
                        return ['tipo' => 'error', 'mensaje' => 'Error al modificar el almacen'];
                    }
                case 'eliminar':
                    if (empty($nombre)) {
                        return ['tipo' => 'error', 'mensaje' => 'Por favor, ingresá el nombre del almacen que querés eliminar.'];
                    }

                    $almacenActual = $this->almacenDAO->getAlmacenByNombre($nombre);

                    if (!$almacenActual) {
                        return ['tipo' => 'error', 'mensaje' => 'Almacen no existe para modificar'];
                    }

                    if ($this->almacenDAO->eliminarAlmacen($nombre)) {
                        return ['tipo' => 'success', 'mensaje' => 'Almacen eliminado correctamente'];
                    } else {
                        return ['tipo' => 'error', 'mensaje' => 'Error al eliminar el almacen'];
                    }
                default:
                    return ['tipo' => 'error', 'mensaje' => 'Acción no válida.'];

            }
        }
        return null;
    }
    public function obtenerAlmacenes()
    {
        return $this->almacenDAO->getAllAlmacenes();
    }
}