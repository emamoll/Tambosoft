<?php

require_once __DIR__ . "../../../servicios/databaseFactory.php";
require_once __DIR__ . "../../../servicios/databaseConnectionInterface.php";

class AlmacenCrearTabla
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function crearTablaAlmacen()
    {
        $this->db = DatabaseFactory::createDatabaseConnection('mysql');
        $conn = $this->db->connect();
        $sql = "CREATE TABLE IF NOT EXISTS almacenes(
            id INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(255) NOT NULL UNIQUE,
            alimento_id INT NOT NULL,
            stock INT NOT NULL,
            campo_id INT NOT NULL,
            FOREIGN KEY (alimento_id) REFERENCES alimentos(id),
            FOREIGN KEY (campo_id) REFERENCES campos(id))";
        $conn->query($sql);
        $conn->close();
    }
}