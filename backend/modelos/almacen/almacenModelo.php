<?php

class Almacen
{
    private $id;
    private $nombre;
    private $alimento_id;
    private $stock;
    private $campo_id;

    public function __construct($id = null, $nombre = null, $alimento_id = null, $stock = null, $campo_id = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->alimento_id = $alimento_id;
        $this->stock = $stock;
        $this->campo_id = $campo_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getAlimento_id()
    {
        return $this->alimento_id;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function getCampo_id()
    {
        return $this->campo_id;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setAlimento_id($alimento_id)
    {
        $this->alimento_id = $alimento_id;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function setCampo_id($campo_id)
    {
        $this->campo_id = $campo_id;
    }

}