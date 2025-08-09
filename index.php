<?php
// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verificar si los archivos existen
if (!file_exists('includes/database.php')) {
    die('Error: No se encuentra el archivo includes/database.php');
}
if (!file_exists('includes/funciones.php')) {
    die('Error: No se encuentra el archivo includes/funciones.php');
}

include 'includes/database.php';
include 'includes/funciones.php';

// Usar el usuario logueado
$usuario_id = $_SESSION['usuario_id'];

// Manejar eliminación
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
    $id_eliminar = (int)$_GET['eliminar'];
    if (eliminarContacto($id_eliminar, $usuario_id)) {
        echo "<script>alert('Contacto eliminado exitosamente');</script>";
    }
    // Redirigir para limpiar la URL
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

// Obtener contactos del usuario logueado
$contactos = obtenerContactos($usuario_id);
?>
<html>
<head>
    <title>Mis Contactos - Agenda</title>
</head>
<body>
    <h1>Mis Contactos</h1>

    <p><b>Bienvenido:</b> <?php echo htmlspecialchars($_SESSION['nombre']); ?> (<?php echo htmlspecialchars($_SESSION['email']); ?>)</p>

    <p>
        <a href="agregar.php">Agregar Contacto</a> | 
        <a href="editar_perfil.php">Editar Perfil</a> | 
        <a href="logout.php">Cerrar Sesión</a>
    </p>
        
    <?php if (empty($contactos)): ?>
        <h3>No hay contactos registrados</h3>
        <p><a href="agregar.php"><b>Agregar tu primer contacto</b></a></p>
    <?php else: ?>
        <p><b>Total de contactos:</b> <?php echo count($contactos); ?></p>
        
        <table border="1">
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Fecha creación</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($contactos as $contacto): ?>
            <tr>
                <td><?php echo htmlspecialchars($contacto['nombre']); ?></td>
                <td><?php echo htmlspecialchars($contacto['telefono'] ?: 'Sin teléfono'); ?></td>
                <td><?php echo htmlspecialchars($contacto['email'] ?: 'Sin email'); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($contacto['fecha_creacion'])); ?></td>
                <td>
                    <a href="editar.php?id=<?php echo $contacto['id']; ?>">Editar</a> | 
                    <a href="index.php?eliminar=<?php echo $contacto['id']; ?>" 
                       onclick="return confirm('Estás seguro de eliminar este contacto?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>