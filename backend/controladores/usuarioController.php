<?php

require_once __DIR__ . '../../DAOS/usuarioDAO.php';

class UsuarioController
{
  private $usuarioDAO;

  public function __construct()
  {
    $this->usuarioDAO = new UsuarioDAO();
  }

  public function registrarUsuario($username, $email, $password, $rol_id, $token)
  {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $existeUsername = $this->usuarioDAO->getUsuarioByUsername($username);
    $existeEmail = $this->usuarioDAO->getUsuarioByEmail($email);

    if (!$existeUsername || !$existeEmail) {
      $this->usuarioDAO->verificarRoles();
      $usuario = new Usuario(null, $username, $email, $hash, $rol_id, $token);
      return $this->usuarioDAO->registrarUsuario($usuario);
    }

    return null;
  }

  public function loginUsuario($username, $password)
  {
    $usuario = $this->usuarioDAO->getUsuarioByUsername($username);

    if ($usuario && password_verify($password, $usuario->getPassword())) {
      // Generar token
      $token = bin2hex(random_bytes(32));
      $usuario->setToken($token);

      // Guardar token en base de datos
      $this->usuarioDAO->actualizarToken($usuario->getId(), $token);

      // Guardar token en la sesión
      $_SESSION['token'] = $token;

      return $usuario;
    }

    return null;
  }

  public function getUsuarioPorToken($token)
  {
    return $this->usuarioDAO->getUsuarioPorToken($token);
  }
}
