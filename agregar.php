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

// Verificar archivos
if (!file_exists('includes/database.php')) {
    die('Error: No se encuentra el archivo includes/database.php');
}
if (!file_exists('includes/funciones.php')) {
    die('Error: No se encuentra el archivo includes/funciones.php');
}

include 'includes/database.php';
include 'includes/funciones.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $usuario_id = $_SESSION['usuario_id']; // Usuario logueado
    
    // Validaciones básicas
    if (empty($nombre)) {
        $error = "El nombre es obligatorio.";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El email no es válido.";
    } else {
        // Crear contacto
        if (crearContacto($nombre, $telefono, $email, $usuario_id)) {
            $mensaje = "Contacto agregado exitosamente!";
            // Limpiar campos
            $nombre = $telefono = $email = '';
        } else {
            $error = "Error al agregar el contacto.";
        }
    }
}
?>
<html>
<head>
    <title>Agregar Contacto - Agenda</title>
</head>
<body>
    <h1>Agregar Contacto</h1>

    <p><a href="index.php">Volver a Mis Contactos</a></p>

    <?php if ($mensaje): ?>
        <p><b>Éxito:</b> <?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p><b>Error:</b> <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <p>
            <label>Nombre:</label><br>
            <input type="text" name="nombre" value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>" required>
        </p>
        
        <p>
            <label>Teléfono:</label><br>
            <input type="text" name="telefono" value="<?php echo isset($telefono) ? htmlspecialchars($telefono) : ''; ?>">
        </p>
        
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
        </p>
        
        <p>
            <input type="submit" value="Agregar Contacto">
        </p>
    </form>
</body>
</html>