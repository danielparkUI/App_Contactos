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

include 'includes/database.php';

$usuario_id = $_SESSION['usuario_id'];
$mensaje = '';
$error = '';

// Obtener datos actuales del usuario
try {
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        $error = "Usuario no encontrado.";
    }
} catch (PDOException $e) {
    $error = "Error al obtener datos: " . $e->getMessage();
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contrasena_actual = trim($_POST['contrasena_actual'] ?? '');
    $nueva_contrasena = trim($_POST['nueva_contrasena'] ?? '');
    $confirmar_contrasena = trim($_POST['confirmar_contrasena'] ?? '');
    
    // Validaciones básicas
    if (empty($nombre) || empty($email)) {
        $error = "El nombre y email son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El email no es válido.";
    } else {
        // Verificar si el email ya existe (excepto el usuario actual)
        try {
            $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
            $stmt->execute([$email, $usuario_id]);
            
            if ($stmt->fetch()) {
                $error = "Este email ya está en uso por otro usuario.";
            } else {
                // Si quiere cambiar contraseña
                if (!empty($nueva_contrasena)) {
                    if (empty($contrasena_actual)) {
                        $error = "Debes ingresar tu contraseña actual para cambiarla.";
                    } elseif ($contrasena_actual !== $usuario['contrasena']) {
                        $error = "La contraseña actual es incorrecta.";
                    } elseif (strlen($nueva_contrasena) < 4) {
                        $error = "La nueva contraseña debe tener al menos 4 caracteres.";
                    } elseif ($nueva_contrasena !== $confirmar_contrasena) {
                        $error = "Las nuevas contraseñas no coinciden.";
                    } else {
                        // Actualizar con nueva contraseña (texto plano)
                        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ?, contrasena = ? WHERE id = ?");
                        
                        if ($stmt->execute([$nombre, $email, $nueva_contrasena, $usuario_id])) {
                            $_SESSION['nombre'] = $nombre;
                            $_SESSION['email'] = $email;
                            $usuario['nombre'] = $nombre;
                            $usuario['email'] = $email;
                            $mensaje = "Perfil y contraseña actualizados exitosamente!";
                        } else {
                            $error = "Error al actualizar el perfil.";
                        }
                    }
                } else {
                    // Actualizar solo nombre y email
                    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
                    
                    if ($stmt->execute([$nombre, $email, $usuario_id])) {
                        $_SESSION['nombre'] = $nombre;
                        $_SESSION['email'] = $email;
                        $usuario['nombre'] = $nombre;
                        $usuario['email'] = $email;
                        $mensaje = "Perfil actualizado exitosamente!";
                    } else {
                        $error = "Error al actualizar el perfil.";
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Error de base de datos: " . $e->getMessage();
        }
    }
}
?>
<html>
<head>
    <title>Editar Perfil - Agenda</title>
</head>
<body>
    <h1>Editar Mi Perfil</h1>

    <p><a href="index.php">Volver a Mis Contactos</a></p>

    <?php if ($mensaje): ?>
        <p><b>Éxito:</b> <?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p><b>Error:</b> <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($usuario): ?>
    <form method="POST">
        <h3>Información Básica</h3>
        
        <p>
            <label>Nombre completo:</label><br>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </p>
        
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
        </p>
        
        <hr>
        <h3>Cambiar Contraseña (Opcional)</h3>
        <p><small>Deja en blanco si no quieres cambiar la contraseña</small></p>
        
        <p>
            <label>Contraseña actual:</label><br>
            <input type="password" name="contrasena_actual">
        </p>
        
        <p>
            <label>Nueva contraseña:</label><br>
            <input type="password" name="nueva_contrasena">
        </p>
        
        <p>
            <label>Confirmar nueva contraseña:</label><br>
            <input type="password" name="confirmar_contrasena">
        </p>
        
        <p>
            <input type="submit" value="Actualizar Perfil">
        </p>
    </form>
    <?php endif; ?>
</body>
</html>