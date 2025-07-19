<?php

require_once __DIR__ . "../../DAOS/categoriaDAO.php";
require_once __DIR__ . "../../DAOS/campoDAO.php";
require_once __DIR__ . "../../modelos/potrero/potreroModelo.php";
require_once __DIR__ . "../../controladores/campoController.php";

class CategoriaController
{
    private $categoriaDAO;

    public function __construct()
    {
        $this->categoriaDAO = new CategoriaDAO();
    }

    public function procesarFormulario()
    {
        $mensaje = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accion = $_POST['accion'] ?? '';
            $nombre = trim($_POST['nombre'] ?? '');
            $potrero_nombre = trim($_POST['potrero_nombre'] ?? '');

            switch ($accion) {
                case 'registrar':
                    if (empty($nombre) || empty($potrero_nombre)) {
                        return ['tipo' => 'error', 'mensaje' => 'Por favor, completá todos los campos para registrar.'];
                    }

                    $potreroDAO = new PotreroDAO();
                    $potrero = $potreroDAO->getPotreroByNombre($potrero_nombre);

                    if (!$potrero) {
                        return ['tipo' => 'error', 'mensaje' => 'El potrero seleccionado no existe.'];
                    }

                    $potrero_id = $potrero->getId();
                    $categoriaExistente = $this->categoriaDAO->getCategoriaByPotreroID($potrero->getId());
                    if ($categoriaExistente) {
                        return ['tipo' => 'error', 'mensaje' => 'Ese potrero ya tiene una categoría asignada.'];
                    }

                    $categoriasExistentes = $this->categoriaDAO->getAllCategorias();
                    foreach ($categoriasExistentes as $cE) {
                        if ($cE->getNombre() === $nombre) {
                            return ['tipo' => 'error', 'mensaje' => 'Ya existe una categoría con ese nombre.'];
                        }
                    }
                    $categoria = new Categoria(null, $nombre, $potrero_id);

                    if ($this->categoriaDAO->registrarCategoria($categoria)) {
                        return ['tipo' => 'success', 'mensaje' => 'Categoria registrada correctamente'];
                    } else {
                        return ['tipo' => 'error', 'mensaje' => 'Error: ya existe una categoria con ese nombre'];
                    }
                case 'modificar':
                    if (empty($nombre)) {
                        return ['tipo' => 'error', 'mensaje' => 'Por favor, ingresá el nombre de la categoría que querés modificar.'];
                    }

                    $categoriaActual = $this->categoriaDAO->getCategoriaByNombre($nombre);

                    if (!$categoriaActual) {
                        return ['tipo' => 'error', 'mensaje' => 'La categoría no existe para modificar.'];
                    }

                    // Si el potrero fue cambiado, validar que no esté ocupado por otra categoría
                    if (!empty($potrero_nombre)) {
                        $potreroDAO = new PotreroDAO();
                        $potrero = $potreroDAO->getPotreroByNombre($potrero_nombre);
                        if (!$potrero) {
                            return ['tipo' => 'error', 'mensaje' => 'El potrero no existe.'];
                        }

                        $categoriaConEsePotrero = $this->categoriaDAO->getCategoriaByPotreroID($potrero->getId());

                        if ($categoriaConEsePotrero && $categoriaConEsePotrero['id'] !== $categoriaActual->getId()) {
                            return ['tipo' => 'error', 'mensaje' => 'Ese potrero ya tiene una categoría asignada.'];
                        }

                        $potrero_id_nuevo = $potrero->getId();
                    } else {
                        $potrero_id_nuevo = $categoriaActual->getPotrero_id();
                    }

                    $categoriaModificada = new Categoria(null, $nombre, $potrero_id_nuevo);

                    if ($this->categoriaDAO->modificarCategoria($categoriaModificada)) {
                        return ['tipo' => 'success', 'mensaje' => 'Categoría modificada correctamente'];
                    } else {
                        return ['tipo' => 'error', 'mensaje' => 'Error al modificar la categoría'];
                    }
                case 'eliminar':
                    if (empty($nombre)) {
                        return ['tipo' => 'error', 'mensaje' => 'Por favor, ingresá el nombre de la categoria que querés eliminar.'];
                    }

                    $categoriaActual = $this->categoriaDAO->getCategoriaByNombre($nombre);

                    if (!$categoriaActual) {
                        return ['tipo' => 'error', 'mensaje' => 'Categoria no existe para modificar'];
                    }

                    if ($this->categoriaDAO->eliminarCategoria($nombre)) {
                        return ['tipo' => 'success', 'mensaje' => 'Categoria eliminado correctamente'];
                    } else {
                        return ['tipo' => 'error', 'mensaje' => 'Error al eliminar la categoria'];
                    }
                default:
                    return ['tipo' => 'error', 'mensaje' => 'Acción no válida.'];
            }
        }
        return null;
    }

    public function obtenerCategorias()
    {
        return $this->categoriaDAO->getAllCategorias();
    }

    public function getCategoriasPorIds(array $ids): array
    {
        return $this->categoriaDAO->getCategoriasPorIds($ids);
    }

    public function obtenerCampoPorCategoriaNombre(string $categoriaNombre)
    {
        $categoria = $this->categoriaDAO->getCategoriaByNombre($categoriaNombre);
        if ($categoria) {
            $campoId = $categoria->getPotrero_id();
            $campoController = new CampoController();
            $campo = $campoController->getCampoById($campoId);
            if ($campo) {
                return $campo->getNombre();
            }
        }
        return null;
    }
}