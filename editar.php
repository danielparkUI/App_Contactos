<?php
session_start();

// Verificar si está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include 'includes/database.php';
include 'includes/funciones.php';

$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';
$error = '';

// Verificar que se envió un ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$contacto_id = (int)$_GET['id'];

// Obtener el contacto (solo si pertenece al usuario)
$stmt = $conn->prepare("SELECT * FROM contactos WHERE id = ? AND usuario_id = ?");
$stmt->execute([$contacto_id, $usuario_id]);
$contacto = $stmt->fetch();

if (!$contacto) {
    header("Location: index.php");
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($nombre)) {
        $error = "El nombre es obligatorio.";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El email no es válido.";
    } else {
        if (actualizarContacto($contacto_id, $nombre, $telefono, $email, $usuario_id)) {
            $mensaje = "Contacto actualizado exitosamente!";
            // Actualizar los datos mostrados
            $contacto['nombre'] = $nombre;
            $contacto['telefono'] = $telefono;
            $contacto['email'] = $email;
        } else {
            $error = "Error al actualizar el contacto.";
        }
    }
}
?>
<html>
<head>
    <title>Editar Contacto - Agenda</title>
</head>
<body>
    <h1>Editar Contacto</h1>

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
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($contacto['nombre']); ?>" required>
        </p>
        
        <p>
            <label>Teléfono:</label><br>
            <input type="text" name="telefono" value="<?php echo htmlspecialchars($contacto['telefono']); ?>">
        </p>
        
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($contacto['email']); ?>">
        </p>
        
        <p>
            <input type="submit" value="Actualizar Contacto">
        </p>
    </form>
</body>
</html>