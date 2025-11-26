<?php
// /SisCap/cursos/profesores.php
require_once __DIR__ . '/../includes/header.php';

$sql = "
    SELECT 
        p.IdProf,
        p.BioProf,
        p.EspProf,
        u.NomUsu,
        u.ApeUsu,
        COUNT(c.IdCur) AS total_cursos
    FROM Profesor p
    INNER JOIN Usuario u ON p.IdUsu = u.IdUsu
    LEFT JOIN Cursos c ON c.IdProf = p.IdProf
    GROUP BY p.IdProf, p.BioProf, p.EspProf, u.NomUsu, u.ApeUsu
    ORDER BY u.NomUsu, u.ApeUsu
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$profesores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="profesores-section">
    <h1>Profesores</h1>

    <?php if (empty($profesores)): ?>
        <p>No hay profesores registrados.</p>
    <?php else: ?>
        <div class="profesores-grid">
            <?php foreach ($profesores as $prof): ?>
                <article class="prof-card">
                    <h3><?= e($prof['NomUsu'] . ' ' . $prof['ApeUsu']) ?></h3>
                    <p><strong>Especialidad:</strong> <?= e($prof['EspProf']) ?></p>
                    <p><strong>Cursos dictados:</strong> <?= (int)$prof['total_cursos'] ?></p>
                    <?php if (!empty($prof['BioProf'])): ?>
                        <p class="bio"><?= nl2br(e($prof['BioProf'])) ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php
require_once __DIR__ . '/../includes/footer.php';
