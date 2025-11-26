<?php
// /SisCap/cursos/paquetes.php
require_once __DIR__ . '/../includes/header.php';

$sql = "
    SELECT 
        p.IdPaq,
        p.NomPaq,
        p.DescPaq,
        p.DurPaq,
        p.CostoPaq,
        COUNT(cp.IdCur) AS total_cursos
    FROM PaqueteCursos p
    LEFT JOIN CursoPaquete cp ON p.IdPaq = cp.IdPaq
    GROUP BY p.IdPaq, p.NomPaq, p.DescPaq, p.DurPaq, p.CostoPaq
    ORDER BY p.NomPaq
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$paquetes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="paquetes-section">
    <h1>Paquetes de cursos</h1>

    <?php if (empty($paquetes)): ?>
        <p>No hay paquetes de cursos registrados.</p>
    <?php else: ?>
        <div class="paquetes-grid">
            <?php foreach ($paquetes as $paq): ?>
                <article class="paq-card">
                    <h3><?= e($paq['NomPaq']) ?></h3>
                    <p><strong>Duración:</strong> <?= e($paq['DurPaq']) ?></p>
                    <p><strong>Costo:</strong> S/ <?= number_format($paq['CostoPaq'], 2) ?></p>
                    <p><strong>Cantidad de cursos:</strong> <?= (int)$paq['total_cursos'] ?></p>
                    <?php if (!empty($paq['DescPaq'])): ?>
                        <p class="descripcion"><?= nl2br(e($paq['DescPaq'])) ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php
require_once __DIR__ . '/../includes/footer.php';
