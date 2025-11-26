<?php
// /SisCap/profesores/index.php
require_once __DIR__ . '/../includes/header.php';

$sql = "
    SELECT p.IdProf, u.NomUsu, u.ApeUsu, p.BioProf, p.EspProf
    FROM Profesor p
    INNER JOIN Usuario u ON p.IdUsu = u.IdUsu
    ORDER BY u.NomUsu
";
$profesores = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="profesores-section">
    <h1>Profesores</h1>
    <div class="profesores-grid">
        <?php foreach ($profesores as $prof): ?>
            <article class="profesor-card">
                <div class="profesor-avatar">
                    <?= strtoupper(substr($prof['NomUsu'], 0, 1)) ?>
                </div>
                <h3><?= htmlspecialchars($prof['NomUsu'] . ' ' . $prof['ApeUsu']) ?></h3>
                <p class="prof-esp"><?= htmlspecialchars($prof['EspProf']) ?></p>
                <p class="prof-bio">
                    <?= nl2br(htmlspecialchars(mb_strimwidth($prof['BioProf'] ?? '', 0, 200, '...'))) ?>
                </p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
