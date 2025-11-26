<?php
// /SisCap/panel/profesor.php
require_once __DIR__ . '/../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'profesor') {
    header('Location: ' . BASE_URL . '/auth/login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$idProf  = $usuario['id_prof'];

$stmt = $pdo->prepare("
    SELECT c.*
    FROM Cursos c
    WHERE c.IdProf = :idp
    ORDER BY c.FechaCur DESC
");
$stmt->execute([':idp' => $idProf]);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="panel-estudiante">
    <div class="panel-header">
        <h1>Cursos que dicto</h1>
        <p>Hola, <strong><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']) ?></strong></p>
    </div>

    <?php if (empty($cursos)): ?>
        <p>No tienes cursos registrados.</p>
    <?php else: ?>
        <div class="cursos-grid">
            <?php foreach ($cursos as $curso): ?>
                <article class="curso-card progreso-card">
                    <div class="curso-card-inner no-flip">
                        <div class="curso-card-front">
                            <div class="curso-info">
                                <h3><?= htmlspecialchars($curso['TituloCur']) ?></h3>
                                <p><?= nl2br(htmlspecialchars(mb_strimwidth($curso['DescCur'], 0, 150, '...'))) ?></p>
                                <p class="curso-precio">S/ <?= number_format($curso['PrecioCur'], 2) ?></p>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
