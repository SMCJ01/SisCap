<?php
// /SisCap/cursos/index.php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/imagenes.php';

// Filtros
$nivel     = $_GET['nivel'] ?? '';
$categoria = $_GET['categoria'] ?? '';
$precioMin = $_GET['pmin'] ?? '';
$precioMax = $_GET['pmax'] ?? '';
$page      = max(1, (int)($_GET['page'] ?? 1));
$perPage   = 10;
$offset    = ($page - 1) * $perPage;

// Cargar opciones de filtros
$niveles = $pdo->query("SELECT IdNivel, NomNivel FROM Niveles ORDER BY NomNivel")->fetchAll(PDO::FETCH_ASSOC);
$tipos   = $pdo->query("SELECT IdTipoCur, NomTipoCur FROM TipoCurso ORDER BY NomTipoCur")->fetchAll(PDO::FETCH_ASSOC);

// Construir WHERE
$where  = '1=1';
$params = [];

if ($nivel !== '') {
    $where .= " AND c.IdNivel = :nivel";
    $params[':nivel'] = (int)$nivel;
}
if ($categoria !== '') {
    $where .= " AND c.IdTipoCur = :cat";
    $params[':cat'] = (int)$categoria;
}
if ($precioMin !== '') {
    $where .= " AND c.PrecioCur >= :pmin";
    $params[':pmin'] = (float)$precioMin;
}
if ($precioMax !== '') {
    $where .= " AND c.PrecioCur <= :pmax";
    $params[':pmax'] = (float)$precioMax;
}

// Contar total
$sqlCount = "
    SELECT COUNT(*) 
    FROM Cursos c
    WHERE $where
";
$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute($params);
$totalCursos = (int)$stmtCount->fetchColumn();
$totalPages  = max(1, (int)ceil($totalCursos / $perPage));

// Obtener cursos
$sql = "
    SELECT 
        c.IdCur, c.TituloCur, c.DescCur, c.PrecioCur, c.FechaCur,
        n.NomNivel,
        t.NomTipoCur,
        u.NomUsu, u.ApeUsu
    FROM Cursos c
    INNER JOIN Niveles n ON c.IdNivel = n.IdNivel
    INNER JOIN TipoCurso t ON c.IdTipoCur = t.IdTipoCur
    INNER JOIN Profesor p ON c.IdProf = p.IdProf
    INNER JOIN Usuario u ON p.IdUsu = u.IdUsu
    WHERE $where
    ORDER BY c.FechaCur DESC
    LIMIT :offset, :per
";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->bindValue(':per', (int)$perPage, PDO::PARAM_INT);
$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="cursos-section">
    <h1>Cursos disponibles</h1>
    <p class="subtitulo">Encuentra el curso que necesitas y empieza a aprender hoy.</p>

    <form method="GET" class="filtros-form">
        <div class="filtro">
            <label for="nivel">Nivel</label>
            <select name="nivel" id="nivel">
                <option value="">Todos</option>
                <?php foreach ($niveles as $n): ?>
                    <option value="<?= $n['IdNivel'] ?>" <?= $nivel == $n['IdNivel'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($n['NomNivel']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filtro">
            <label for="categoria">Categoría</label>
            <select name="categoria" id="categoria">
                <option value="">Todas</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= $t['IdTipoCur'] ?>" <?= $categoria == $t['IdTipoCur'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['NomTipoCur']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filtro">
            <label for="pmin">Precio mín.</label>
            <input type="number" step="0.01" name="pmin" id="pmin" value="<?= htmlspecialchars($precioMin) ?>">
        </div>
        <div class="filtro">
            <label for="pmax">Precio máx.</label>
            <input type="number" step="0.01" name="pmax" id="pmax" value="<?= htmlspecialchars($precioMax) ?>">
        </div>

        <button type="submit" class="btn-secondary">Aplicar</button>
    </form>

    <?php if (empty($cursos)): ?>
        <p>No se encontraron cursos con esos filtros.</p>
    <?php else: ?>
        <div class="cursos-grid">
            <?php foreach ($cursos as $curso): 
                $img = obtenerImagenCurso($curso['TituloCur']);
            ?>
                <article class="curso-card scroll-reveal">
                    <div class="loader-overlay"></div>
                    <div class="curso-card-inner">
                        <!-- Frente -->
                        <div class="curso-card-front">
                            <img src="<?= $img ?>" alt="Imagen del curso" class="curso-img">
                            <div class="curso-info">
                                <h3><?= htmlspecialchars($curso['TituloCur']) ?></h3>
                                <p class="curso-nivel"><?= htmlspecialchars($curso['NomNivel']) ?></p>
                                <p class="curso-cat"><?= htmlspecialchars($curso['NomTipoCur']) ?></p>
                                <p class="curso-prof">
                                    Por <?= htmlspecialchars($curso['NomUsu'] . ' ' . $curso['ApeUsu']) ?>
                                </p>
                                <p class="curso-precio">S/ <?= number_format($curso['PrecioCur'], 2) ?></p>
                            </div>
                        </div>
                        <!-- Dorso -->
                        <div class="curso-card-back">
                            <h3><?= htmlspecialchars($curso['TituloCur']) ?></h3>
                            <p><?= nl2br(htmlspecialchars(mb_strimwidth($curso['DescCur'], 0, 200, '...'))) ?></p>
                            <a href="detalle.php?id=<?= (int)$curso['IdCur'] ?>" class="btn-primary small">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Paginación -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">« Anterior</a>
            <?php endif; ?>

            <span>Página <?= $page ?> de <?= $totalPages ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Siguiente »</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
