<?php
// /SisCap/cursos/inscribirse.php
require_once __DIR__ . '/../includes/header.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: ' . BASE_URL . '/auth/login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$idUsu   = $usuario['id'];

$idCur = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($idCur <= 0) {
    echo "<p>Curso no válido.</p>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Traer info del curso
$stmt = $pdo->prepare("SELECT * FROM Cursos WHERE IdCur = :id");
$stmt->execute([':id' => $idCur]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    echo "<p>Curso no encontrado.</p>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo = $_POST['metodo'] ?? '';

    if ($metodo === '') {
        $error = 'Debe seleccionar un método de pago.';
    } else {
        try {
            // Verificar si ya está inscrito
            $check = $pdo->prepare("SELECT IdIns FROM Inscripcion WHERE IdUsu = :u AND IdCur = :c");
            $check->execute([':u' => $idUsu, ':c' => $idCur]);
            $ins = $check->fetch(PDO::FETCH_ASSOC);

            if ($ins) {
                $idIns = $ins['IdIns'];
            } else {
                // Insertar inscripción
                $insStmt = $pdo->prepare("
                    INSERT INTO Inscripcion (IdUsu, IdCur, FechaIns, EstadoIns)
                    VALUES (:u, :c, CURDATE(), 'Inscrito')
                ");
                $insStmt->execute([':u' => $idUsu, ':c' => $idCur]);
                $idIns = $pdo->lastInsertId();
            }

            // Insertar pago
            $pagStmt = $pdo->prepare("
                INSERT INTO Pago (IdIns, MontoPag, FechaPag, MetodoPag, EstadoPag)
                VALUES (:ins, :monto, CURDATE(), :metodo, 'Pagado')
            ");
            $pagStmt->execute([
                ':ins'    => $idIns,
                ':monto'  => $curso['PrecioCur'],
                ':metodo' => $metodo
            ]);

            $mensaje = 'Inscripción realizada y pago registrado correctamente.';
        } catch (PDOException $e) {
            $error = 'Error al inscribirse: ' . $e->getMessage();
        }
    }
}
?>

<section class="inscripcion-section">
    <h1>Inscribirme en: <?= htmlspecialchars($curso['TituloCur']) ?></h1>

    <?php if ($mensaje): ?>
        <div class="alert success"><?= htmlspecialchars($mensaje) ?></div>
        <a href="<?= BASE_URL ?>/panel/estudiante.php" class="btn-primary">Ir a mis cursos</a>
    <?php else: ?>
        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <p>Precio del curso: <strong>S/ <?= number_format($curso['PrecioCur'], 2) ?></strong></p>

        <form method="POST" class="auth-form">
            <label for="metodo">Método de pago</label>
            <select name="metodo" id="metodo" required>
                <option value="">Seleccione...</option>
                <option value="Tarjeta de crédito">Tarjeta de crédito</option>
                <option value="Tarjeta de débito">Tarjeta de débito</option>
                <option value="Transferencia bancaria">Transferencia bancaria</option>
                <option value="Yape">Yape</option>
                <option value="Plin">Plin</option>
            </select>

            <button type="submit" class="btn-primary full">Confirmar inscripción</button>
        </form>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
