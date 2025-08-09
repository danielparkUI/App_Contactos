<?php
require_once __DIR__ . '/database.php';

function crearContacto($nombre, $telefono, $email, $usuario_id) {
    global $conn;
    try {
        $sql = "INSERT INTO contactos (nombre, telefono, email, usuario_id)
         VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$nombre, $telefono, $email, $usuario_id]);
    } catch (PDOException $e) {
        echo "Error al crear contacto: " . $e->getMessage();
        return false;
    }
}

function obtenerContactos($usuario_id) {
    global $conn;
    try {
        $sql = "SELECT * FROM contactos WHERE usuario_id = ? ORDER BY nombre ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Error al obtener contactos: " . $e->getMessage();
        return [];
    }
}

function eliminarContacto($id, $usuario_id) {
    global $conn;
    try {
        $sql = "DELETE FROM contactos WHERE id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id, $usuario_id]);
    } catch (PDOException $e) {
        echo "Error al eliminar contacto: " . $e->getMessage();
        return false;
    }
}       

function actualizarContacto($id, $nombre, $telefono, $email, $usuario_id) {
    global $conn;
    try {
        $sql = "UPDATE contactos SET nombre = ?, telefono = ?, email = ? WHERE id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$nombre, $telefono, $email, $id, $usuario_id]);
    } catch (PDOException $e) {
        echo "Error al actualizar contacto: " . $e->getMessage();
        return false;
    }
}
?>