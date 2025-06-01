<?php

class Categoria
{
    private $id;
    private $nombre;
    private $potrero_id;

    public function __construct($id = null, $nombre = null, $potrero_id = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->potrero_id = $potrero_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getPotrero_id()
    {
        return $this->potrero_id;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setPotrero_id($potrero_id)
    {
        $this->potrero_id = $potrero_id;
    }

}
