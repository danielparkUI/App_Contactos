<?php
// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/database.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $confirmar = trim($_POST['confirmar'] ?? '');
    
    // Validaciones
    if (empty($nombre) || empty($email) || empty($contrasena)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($contrasena !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($contrasena) < 4) {
        $error = "La contraseña debe tener al menos 4 caracteres.";
    } else {
        // Verificar si el email ya existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $error = "Este email ya está registrado.";
        } else {
            // Crear usuario con contraseña en texto plano
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$nombre, $email, $contrasena])) {
                $mensaje = "Usuario creado exitosamente! Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al crear el usuario.";
            }
        }
    }
}
?>
<html>
<head>
    <title>Agenda Pro - Registro</title>
</head>
<body>
    <h1>Crear Cuenta Nueva</h1>

    <p><a href="login.php">Iniciar Sesión</a></p>

    <?php if ($mensaje): ?>
        <p><b>Éxito:</b> <?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p><b>Error:</b> <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <p>
            <label>Nombre completo:</label><br>
            <input type="text" name="nombre" value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>" required>
        </p>
        
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
        </p>
        
        <p>
            <label>Contraseña:</label><br>
            <input type="password" name="contrasena" required>
        </p>
        
        <p>
            <label>Confirmar contraseña:</label><br>
            <input type="password" name="confirmar" required>
        </p>
        
        <p>
            <input type="submit" value="Crear Cuenta">
        </p>
    </form>
</body>
</html>