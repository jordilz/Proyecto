<?php
class UsuarioModel
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerUsuarioPorEmail($email)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /*     public function existeEmail($email)
    {
        $stmt = $this->conexion->prepare("SELECT id FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    } */

    public function existeEmail($email)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0;
    }

    public function registrar($nombre, $email, $password)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conexion->prepare("INSERT INTO usuario (nombre, email, contrasena, fechaRegistro) VALUES (?, ?, ?, CURDATE())");
        $stmt->bind_param("sss", $nombre, $email, $hashed);
        return $stmt->execute();
    }

    public function obtenerUsuarioPorId($id)
    {
        $stmt = $this->conexion->prepare("SELECT id, nombre, email, foto FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarPerfil($id, $nombre, $email)
    {
        $stmt = $this->conexion->prepare("UPDATE usuario SET nombre = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nombre, $email, $id);
        return $stmt->execute();
    }

    public function cambiarContrasena($id, $nuevaPass)
    {
        $hash = password_hash($nuevaPass, PASSWORD_DEFAULT);
        $stmt = $this->conexion->prepare("UPDATE usuario SET contrasena = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $id);
        return $stmt->execute();
    }
}
