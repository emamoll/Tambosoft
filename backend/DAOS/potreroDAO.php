<?php

require_once __DIR__ . '../../servicios/databaseFactory.php';
require_once __DIR__ . '../../modelos/potrero/potreroTabla.php';
require_once __DIR__ . '../../modelos/potrero/potreroModelo.php';

class PotreroDAO
{
  private $db;
  private $conn;
  private $crearTabla;

  public function __construct()
  {
    $this->db = DatabaseFactory::createDatabaseConnection('mysql');
    $this->crearTabla = new PotreroCrearTabla($this->db);
    $this->crearTabla->crearTablaPotrero();
    $this->conn = $this->db->connect();
  }

  public function getAllPotreros()
  {
    $sql = "SELECT * FROM potreros";
    $result = $this->conn->query($sql);

    if (!$result) {
      die("Error en la consulta: " . $this->conn->error);
    }

    $potreros = [];

    while ($row = $result->fetch_assoc()) {
      $potreros[] = new Potrero($row['id'], $row['nombre'], $row['superficie'], $row['pastura'], $row['campo_id']);
    }

    return $potreros;
  }

  public function getPotreroById($id)
  {
    $sql = "SELECT * FROM potreros WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows() === 0) {
      return null;
    }

    $stmt->bind_result($id, $nombre, $superficie, $pastura, $campo_id);
    $stmt->fetch();

    return new Potrero($id, $nombre, $superficie, $pastura, $campo_id);
  }

  public function getPotreroByNombre($nombre)
  {
    $sql = "SELECT * FROM potreros WHERE nombre = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows() === 0) {
      return null;
    }

    $stmt->bind_result($id, $nombre, $superficie, $pastura, $campo_id);
    $stmt->fetch();

    return new Potrero($id, $nombre, $superficie, $pastura, $campo_id);
  }

  public function getPotreroByPastura($pastura)
  {
    $sql = "SELECT * FROM potreros WHERE pastura = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $pastura);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows() === 0) {
      return null;
    }

    $stmt->bind_result($id, $nombre, $superficie, $pastura, $campo_id);
    $stmt->fetch();

    return new Potrero($id, $nombre, $superficie, $pastura, $campo_id);
  }

  public function registrarPotrero(Potrero $p)
  {
    $sqlVer = "SELECT id FROM potreros WHERE nombre = ?";
    $stmtVer = $this->conn->prepare($sqlVer);
    $nombre = $p->getNombre();
    $stmtVer->bind_param("s", $nombre);
    $stmtVer->execute();
    $stmtVer->store_result();

    if ($stmtVer->num_rows > 0) {
      return false;
    }

    $stmtVer->close();

    $sql = "INSERT INTO potreros (nombre, superficie, pastura, campo_id) VALUES (?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $n = $p->getNombre();
    $s = $p->getSuperficie();
    $pas = $p->getPastura();
    $c_i = $p->getCampo_id();
    $stmt->bind_param("sssi", $n, $s, $pas, $c_i);

    if (!$stmt->execute()) {
      die("Error en execute (inserción): " . $stmt->error);
    }

    $stmt->close();

    return true;
  }

  public function modificarPotrero(Potrero $p)
  {
    $sql = "UPDATE potreros SET superficie = ?, pastura = ?, campo_id = ? WHERE nombre = ?";
    $stmt = $this->conn->prepare($sql);
    $n = $p->getNombre();
    $s = $p->getSuperficie();
    $pas = $p->getPastura();
    $c_i = $p->getCampo_id();
    $stmt->bind_param("ssis", $s, $pas, $c_i, $n);

    return $stmt->execute();
  }

  public function eliminarPotrero($nombre)
  {
    $sql = "DELETE FROM potreros WHERE nombre = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $nombre);

    return $stmt->execute();
  }
}
