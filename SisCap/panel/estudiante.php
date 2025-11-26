<?php
// /SisCap/panel/estudiante.php
require_once __DIR__ . '/../includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'estudiante') {
    header('Location: ' . BASE_URL . '/auth/login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$idUsu   = $usuario['id'];
$nombre  = htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido'], ENT_QUOTES, 'UTF-8');

// Cursos inscritos + progreso
$sql = "
    SELECT 
        i.IdIns,
        i.IdCur,
        i.FechaIns,
        c.TituloCur,
        c.DescCur,
        c.PrecioCur,
        AVG(dn.CalifDetNot) AS promedio_notas
    FROM Inscripcion i
    INNER JOIN Cursos c ON i.IdCur = c.IdCur
    LEFT JOIN Notas n ON n.IdIns = i.IdIns
    LEFT JOIN DetalleNotas dn ON dn.IdNot = n.IdNot
    WHERE i.IdUsu = :idUsu
    GROUP BY i.IdIns, i.IdCur, i.FechaIns, c.TituloCur, c.DescCur, c.PrecioCur
    ORDER BY i.FechaIns DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':idUsu' => $idUsu]);
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="panel-estudiante">
    <div class="panel-header">
        <h1>Mis cursos</h1>
        <p>Hola, <strong><?= $nombre ?></strong> 👋</p>
    </div>

    <?php if (empty($cursos)): ?>
        <p>No tienes cursos inscritos todavía.</p>
        <a href="<?= BASE_URL ?>/cursos/index.php" class="btn-primary">Ver catálogo de cursos</a>
    <?php else: ?>
        <div class="cursos-grid">
            <?php foreach ($cursos as $curso): 
                $progreso = 0;
                if ($curso['promedio_notas'] !== null) {
                    $progreso = min(100, max(0, ($curso['promedio_notas'] / 20) * 100));
                }
            ?>
                <article class="curso-card progreso-card">
                    <div class="curso-card-inner no-flip">
                        <div class="curso-card-front">
                            <div class="curso-info">
                                <h3><?= htmlspecialchars($curso['TituloCur']) ?></h3>
                                <p class="fecha-inscripcion">
                                    Inscrito el: <?= htmlspecialchars($curso['FechaIns']) ?>
                                </p>
                                <p class="descripcion">
                                    <?= nl2br(htmlspecialchars(mb_strimwidth($curso['DescCur'], 0, 150, '...'))) ?>
                                </p>
                                <p class="curso-precio">
                                    Precio: S/ <?= number_format($curso['PrecioCur'], 2) ?>
                                </p>

                                <div class="progreso">
                                    <div class="progreso-barra">
                                        <div class="progreso-barra-inner" style="width: <?= (int)$progreso ?>%;"></div>
                                    </div>
                                    <span><?= (int)$progreso ?>% completado</span>
                                </div>

                                <a href="<?= BASE_URL ?>/cursos/detalle.php?id=<?= (int)$curso['IdCur'] ?>" class="btn-secondary small">
                                    Ir al curso
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
