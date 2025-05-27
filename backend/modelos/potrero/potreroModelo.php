<?php

class Potrero
{
  private $id;
  private $nombre;
  private $superficie;
  private $pastura;
  private $campo_id;

  public function __construct($id = null, $nombre = null, $superficie = null, $pastura = null, $campo_id = null)
  {
    $this->id = $id;
    $this->nombre = $nombre;
    $this->superficie = $superficie;
    $this->pastura = $pastura;
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

  public function getSuperficie()
  {
    return $this->superficie;
  }

  public function getPastura()
  {
    return $this->pastura;
  }

  public function getCampo_id()
  {
    return $this->campo_id;
  }

  public function setNombre($nombre)
  {
    $this->nombre = $nombre;
  }

  public function setSuperficie($superficie)
  {
    $this->superficie = $superficie;
  }

  public function setPastura($pastura)
  {
    $this->pastura = $pastura;
  }

  public function setCampo_id($campo_id)
  {
    $this->campo_id = $campo_id;
  }
}
