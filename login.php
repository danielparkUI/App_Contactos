<?php
// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Si ya está logueado, redirigir
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

include 'includes/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    
    if (empty($email) || empty($contrasena)) {
        $error = "Email y contraseña son obligatorios.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
            
            // Comparación directa sin hash
            if ($usuario && $contrasena === $usuario['contrasena']) {
                // Login exitoso
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['email'] = $usuario['email'];
                
                header("Location: index.php");
                exit;
            } else {
                $error = "Email o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            $error = "Error de conexión: " . $e->getMessage();
        }
    }
}
?>
<html>
<head>
    <title>Agenda - Login</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <p><a href="registro.php">Crear una nueva</a></p>

    <?php if ($error): ?>
        <p><b>Error:</b> <?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST">
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
        </p>
        
        <p>
            <label>Contraseña:</label><br>
            <input type="password" name="contrasena" required>
        </p>
        
        <p>
            <input type="submit" value="Iniciar Sesión">
        </p>
    </form>
</body>
</html>