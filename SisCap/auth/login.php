<?php
// /SisCap/auth/login.php
require_once __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');

    if ($email === '' || $pass === '') {
        $error = 'Debe ingresar correo y contraseña.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE EmailUsu = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['PassUsu'] === $pass) { // demo sin hash
            $idProf = null;
            if ($user['RolUsu'] === 'profesor') {
                $p = $pdo->prepare("SELECT IdProf FROM Profesor WHERE IdUsu = :id");
                $p->execute(['id' => $user['IdUsu']]);
                $prof = $p->fetch(PDO::FETCH_ASSOC);
                if ($prof) {
                    $idProf = $prof['IdProf'];
                }
            }

            $_SESSION['usuario'] = [
                'id'       => $user['IdUsu'],
                'nombre'   => $user['NomUsu'],
                'apellido' => $user['ApeUsu'],
                'email'    => $user['EmailUsu'],
                'rol'      => $user['RolUsu'],
                'id_prof'  => $idProf
            ];

            if ($user['RolUsu'] === 'profesor') {
                header('Location: ' . BASE_URL . '/panel/profesor.php');
            } else {
                header('Location: ' . BASE_URL . '/panel/estudiante.php');
            }
            exit;
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}
?>

<section class="auth-section">
    <h1>Ingresar</h1>
    <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="auth-form">
        <label for="email">Correo electrónico</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="btn-primary full">Ingresar</button>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
