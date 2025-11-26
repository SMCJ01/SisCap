<?php
// /SisCap/paquetes/index.php
require_once __DIR__ . '/../includes/header.php';

$sql = "
    SELECT IdPaq, NomPaq, DescPaq, DurPaq, CostoPaq
    FROM PaqueteCursos
    ORDER BY CostoPaq DESC
";
$paquetes = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="paquetes-section">
    <h1>Paquetes de cursos</h1>
    <div class="paquetes-grid">
        <?php foreach ($paquetes as $p): ?>
            <article class="paquete-card">
                <h3><?= htmlspecialchars($p['NomPaq']) ?></h3>
                <p class="paq-dur">Duración: <?= htmlspecialchars($p['DurPaq']) ?></p>
                <p class="paq-desc">
                    <?= nl2br(htmlspecialchars(mb_strimwidth($p['DescPaq'] ?? '', 0, 200, '...'))) ?>
                </p>
                <p class="paq-precio">Desde S/ <?= number_format($p['CostoPaq'], 2) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
