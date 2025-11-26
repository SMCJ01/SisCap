<?php
// /SisCap/cursos/detalle.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/imagenes.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<p>Curso no encontrado.</p>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$sql = "
    SELECT 
        c.*, 
        n.NomNivel, 
        t.NomTipoCur,
        u.NomUsu, u.ApeUsu
    FROM Cursos c
    INNER JOIN Niveles n ON c.IdNivel = n.IdNivel
    INNER JOIN TipoCurso t ON c.IdTipoCur = t.IdTipoCur
    INNER JOIN Profesor p ON c.IdProf = p.IdProf
    INNER JOIN Usuario u ON p.IdUsu = u.IdUsu
    WHERE c.IdCur = :id
    LIMIT 1
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    echo "<p>Curso no encontrado.</p>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$img = obtenerImagenCurso($curso['TituloCur']);
?>

<section class="curso-detalle">
    <div class="curso-detalle-header">
        <div class="curso-detalle-texto">
            <h1><?= htmlspecialchars($curso['TituloCur']) ?></h1>
            <p class="detalle-nivel"><?= htmlspecialchars($curso['NomNivel']) ?> · <?= htmlspecialchars($curso['NomTipoCur']) ?></p>
            <p class="detalle-prof">Profesor: <?= htmlspecialchars($curso['NomUsu'] . ' ' . $curso['ApeUsu']) ?></p>
            <p class="detalle-precio">Precio: S/ <?= number_format($curso['PrecioCur'], 2) ?></p>
            <a href="inscribirse.php?id=<?= (int)$curso['IdCur'] ?>" class="btn-primary">
                Inscribirme en este curso
            </a>
        </div>
        <div class="curso-detalle-imagen">
            <img src="<?= $img ?>" alt="Imagen del curso">
        </div>
    </div>

    <div class="curso-detalle-descripcion">
        <h2>Descripción del curso</h2>
        <p><?= nl2br(htmlspecialchars($curso['DescCur'])) ?></p>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
