<?php

require_once __DIR__ . '../../../servicios/databaseFactory.php';
require_once __DIR__ . '../../../servicios/databaseConnectionInterface.php';

class UsuarioCrearTabla
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function crearTablaRoles()
  {
    $this->db = DatabaseFactory::createDatabaseConnection('mysql');
    $conn = $this->db->connect();
    $sql = "CREATE TABLE IF NOT EXISTS roles (
              id INT PRIMARY KEY AUTO_INCREMENT, 
              nombre VARCHAR(255) NOT NULL UNIQUE)";

    $conn->query($sql);
    $conn->close();
  }

  public function crearTablaUsuarios()
  {
    $this->db = DatabaseFactory::createDatabaseConnection('mysql');
    $conn = $this->db->connect();
    $sql = "CREATE TABLE IF NOT EXISTS  usuarios (
              id INT PRIMARY KEY AUTO_INCREMENT, 
              username VARCHAR(255) NOT NULL UNIQUE, 
              email VARCHAR(255) NOT NULL UNIQUE, 
              password VARCHAR(255) NOT NULL,
              rol_id INT NOT NULL,
              token VARCHAR(64),
              FOREIGN KEY (rol_id) REFERENCES roles(id))";

    $conn->query($sql);
    $conn->close();
  }
}
