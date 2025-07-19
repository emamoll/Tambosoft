<?php

require_once __DIR__ . "../../servicios/databaseFactory.php";
require_once __DIR__ . "../../modelos/almacen/almacenTabla.php";
require_once __DIR__ . "../../modelos/almacen/almacenModelo.php";

class AlmacenDAO
{
  private $db;
  private $conn;
  private $crearTabla;

  public function __construct()
  {
    $this->db = DatabaseFactory::createDatabaseConnection('mysql');
    $this->crearTabla = new AlmacenCrearTabla($this->db);
    $this->crearTabla->crearTablaAlmacen();
    $this->conn = $this->db->connect();
  }

  public function getAllAlmacenes()
  {
    $sql = "SELECT * FROM almacenes";
    $result = $this->conn->query($sql);

    if (!$result) {
      die("Error en la consulta: " . $this->conn->error);
    }

    $almacenes = [];

    while ($row = $result->fetch_assoc()) {
      $almacenes[] = new Almacen($row['id'], $row['nombre'],$row['alimento_id'], $row['stock'], $row['campo_id']);
    }

    return $almacenes;
  }

  public function getAlmacenById($id)
  {
    $sql = "SELECT * FROM almacenes WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows() === 0) {
      return null;
    }

    $stmt->bind_result($id, $nombre, $alimento_id, $stock, $campo_id);
    $stmt->fetch();

    return new Almacen($id, $nombre, $alimento_id, $stock, $campo_id);
  }

  public function getAlmacenByNombre($nombre)
  {
    $sql = "SELECT * FROM almacenes WHERE nombre = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows() === 0) {
      return null;
    }

    $stmt->bind_result($id, $nombre, $alimento_id, $stock, $campo_id);
    $stmt->fetch();

    return new Almacen($id, $nombre, $alimento_id, $stock, $campo_id);
  }

  public function getAlmacenByCampoId($campo_id)
  {
    $sql = "SELECT * FROM almacenes WHERE campo_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $campo_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $almacenes = [];
    while ($row = $result->fetch_assoc()) {
      $almacenes[] = new Almacen($row['id'], $row['alimento_id'], $row['stock'], $row['campo_id']);
    }

    return $almacenes;
  }

  public function registrarAlmacen(Almacen $a)
  {
    $sqlVer = "SELECT id FROM almacenes WHERE nombre = ?";
    $stmtVer = $this->conn->prepare($sqlVer);
    $nombre = $a->getNombre();
    $stmtVer->bind_param("s", $nombre);
    $stmtVer->execute();
    $stmtVer->store_result();

    if ($stmtVer->num_rows > 0) {
      return false;
    }

    $stmtVer->close();

    $sql = "INSERT INTO almacenes (nombre, alimento_id, stock, campo_id) VALUES (?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $n = $a->getNombre();
    $ali_i = $a->getAlimento_id();
    $s = $a->getStock();
    $c_i = $a->getCampo_id();
    $stmt->bind_param("sisi", $n,$ali_i, $s, $c_i);

    if (!$stmt->execute()) {
      die("Error en execute (inserción): " . $stmt->error);
    }

    $stmt->close();

    return true;
  }

  public function modificarAlmacen(Almacen $a)
  {
    $sql = "UPDATE almacenes SET alimento_id = ?, stock = ?, campo_id = ? WHERE nombre = ?";
    $stmt = $this->conn->prepare($sql);
    $n = $a->getNombre();
    $ali_i = $a->getAlimento_id();
    $s = $a->getStock();
    $c_i = $a->getCampo_id();
    $stmt->bind_param('isis', $ali_i, $s, $c_i, $n);

    return $stmt->execute();
  }

  public function eliminarAlmacen($nombre)
  {
    $sql = "DELETE FROM almacenes WHERE nombre = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $nombre);

    return $stmt->execute();
  }
}