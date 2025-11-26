<?php
// /SisCap/usuarios/registro.php
require_once __DIR__ . '/../includes/header.php';

$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $pass     = trim($_POST['password'] ?? '');

    if ($nombre === '' || $apellido === '' || $email === '' || $pass === '') {
        $error = 'Todos los campos son obligatorios.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO Usuario (NomUsu, ApeUsu, EmailUsu, PassUsu, RolUsu, FechaRegUsu)
                                   VALUES (:nom, :ape, :email, :pass, 'estudiante', CURDATE())");
            $stmt->execute([
                'nom'   => $nombre,
                'ape'   => $apellido,
                'email' => $email,
                'pass'  => $pass
            ]);
            $mensaje = 'Registro exitoso. Ahora puedes ingresar.';
        } catch (PDOException $e) {
            $error = 'Error al registrar: ' . $e->getMessage();
        }
    }
}
?>

<section class="auth-section">
    <h1>Registro de estudiante</h1>

    <?php if ($mensaje): ?>
        <div class="alert success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="auth-form">
        <label for="nombre">Nombres</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="apellido">Apellidos</label>
        <input type="text" name="apellido" id="apellido" required>

        <label for="email">Correo electrónico</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="btn-primary full">Registrarme</button>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
