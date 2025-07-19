<?php

require_once __DIR__ . "../../servicios/databaseFactory.php";
require_once __DIR__ . "../../modelos/categoria/categoriaTabla.php";
require_once __DIR__ . "../../modelos/categoria/categoriaModelo.php";

class CategoriaDAO
{
	private $db;
	private $conn;
	private $crearTabla;

	public function __construct()
	{
		$this->db = DatabaseFactory::createDatabaseConnection('mysql');
		$this->crearTabla = new CategoriaCrearTabla($this->db);
		$this->crearTabla->crearTablaCategoria();
		$this->conn = $this->db->connect();
	}

	public function getAllCategorias()
	{
		$sql = "SELECT * FROM categorias";
		$result = $this->conn->query($sql);

		if (!$result) {
			die("Error en la consulta: " . $this->conn->error);
		}

		$categorias = [];

		while ($row = $result->fetch_assoc()) {
			$categorias[] = new Categoria($row['id'], $row['nombre'], $row['potrero_id']);
		}

		return $categorias;
	}

	public function getCategoriaById($id)
	{
		$sql = "SELECT * FROM categorias WHERE id = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows() === 0) {
			return null;
		}

		$stmt->bind_result($id, $nombre, $potrero_id);
		$stmt->fetch();

		return new Categoria($id, $nombre, $potrero_id);
	}

	public function getCategoriaByNombre($nombre)
	{
		$sql = "SELECT * FROM categorias WHERE nombre = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("s", $nombre);
		$stmt->execute();
		$stmt->store_result();

		if ($stmt->num_rows() === 0) {
			return null;
		}

		$stmt->bind_result($id, $nombre, $potrero_id);
		$stmt->fetch();

		return new Categoria($id, $nombre, $potrero_id);
	}

	public function getCategoriaByPotreroID($potrero_id)
	{
		$sql = "SELECT * FROM categorias WHERE potrero_id = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("i", $potrero_id);
		$stmt->execute();
		$result = $stmt->get_result();

		$categorias = [];
		while ($row = $result->fetch_assoc()) {
			$categorias[] = new Categoria($row['id'], $row['nombre'], $row['potrero_id']);
		}

		return $categorias;
	}

	public function getCategoriasPorIds(array $ids)
	{
		if (empty($ids)) {
			return [];
		}

		// Crear una lista separada por comas de signos de interrogación para prepared statement dinámico
		$placeholders = implode(',', array_fill(0, count($ids), '?'));

		// Preparar la consulta con placeholders
		$sql = "SELECT * FROM categorias WHERE id IN ($placeholders)";
		$stmt = $this->conn->prepare($sql);

		if ($stmt === false) {
			die("Error en prepare: " . $this->conn->error);
		}

		// Construir tipos para bind_param: todos enteros 'i'
		$tipos = str_repeat('i', count($ids));

		// bind_param requiere referencias, así que usamos esta función para pasar un array variable
		$refs = [];
		foreach ($ids as $key => $value) {
			$refs[$key] = &$ids[$key];
		}

		// Ejecutar bind_param dinámico
		array_unshift($refs, $tipos); // agregamos el string de tipos al inicio

		call_user_func_array([$stmt, 'bind_param'], $refs);

		$stmt->execute();

		$result = $stmt->get_result();

		$categorias = [];

		while ($row = $result->fetch_assoc()) {
			$categorias[] = new Categoria($row['id'], $row['nombre'], $row['potrero_id']);
		}

		$stmt->close();

		return $categorias;
	}

	public function registrarCategoria(Categoria $c)
	{
		$sqlVer = "SELECT id FROM categorias WHERE nombre = ? AND potrero_id = ?";
		$stmtVer = $this->conn->prepare($sqlVer);
		$nombre = $c->getNombre();
		$potrero_id = $c->getPotrero_id();
		$stmtVer->bind_param("si", $nombre, $potrero_id);
		$stmtVer->execute();
		$stmtVer->store_result();

		if ($stmtVer->num_rows > 0) {
			return false;
		}

		$stmtVer->close();

		$sql = "INSERT INTO categorias (nombre, potrero_id) VALUES (?, ?)";
		$stmt = $this->conn->prepare($sql);
		$n = $c->getNombre();
		$p_i = $c->getPotrero_id();
		$stmt->bind_param("si", $n, $p_i);

		if (!$stmt->execute()) {
			die("Error en execute (inserción): " . $stmt->error);
		}

		$stmt->close();

		return true;
	}

	public function modificarCategoria(Categoria $c)
	{
		$sql = "UPDATE categorias SET potrero_id = ? WHERE nombre = ?";
		$stmt = $this->conn->prepare($sql);
		$n = $c->getNombre();
		$p_i = $c->getPotrero_id();
		$stmt->bind_param("is", $p_i, $n);

		return $stmt->execute();
	}

	public function eliminarCategoria($nombre)
	{
		$sql = "DELETE FROM categorias WHERE nombre = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("s", $nombre);

		return $stmt->execute();
	}



}