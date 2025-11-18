<?php
class Usuario {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function login($email, $password) {
        $this->db->query('SELECT * FROM usuarios WHERE email = :email AND activo = TRUE');
        $this->db->bind(':email', $email);

        $usuario = $this->db->single();

        if ($usuario && password_verify($password, $usuario->password)) {
            return $usuario;
        }
        return false;
    }

    public function findById($id) {
        $this->db->query('SELECT * FROM usuarios WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function findByEmail($email) {
        $this->db->query('SELECT * FROM usuarios WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function getAll() {
        $this->db->query('SELECT * FROM usuarios ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function create($data) {
        $this->db->query('INSERT INTO usuarios (email, password, nombre, apellidos, dni, telefono, rol)
                         VALUES (:email, :password, :nombre, :apellidos, :dni, :telefono, :rol)');

        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':apellidos', $data['apellidos']);
        $this->db->bind(':dni', $data['dni']);
        $this->db->bind(':telefono', $data['telefono']);
        $this->db->bind(':rol', $data['rol']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $this->db->query('UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos,
                         telefono = :telefono, rol = :rol, activo = :activo WHERE id = :id');

        $this->db->bind(':id', $id);
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':apellidos', $data['apellidos']);
        $this->db->bind(':telefono', $data['telefono']);
        $this->db->bind(':rol', $data['rol']);
        $this->db->bind(':activo', $data['activo']);

        return $this->db->execute();
    }

    public function changePassword($id, $newPassword) {
        $this->db->query('UPDATE usuarios SET password = :password WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':password', password_hash($newPassword, PASSWORD_DEFAULT));
        return $this->db->execute();
    }
}
