<?php
/**
 * VISTA: flyerSearchView.php
 * Búsqueda de flyers con filtros y paginación
 */
 require_once __DIR__.'/../controllers/AuthController.php';
    require_once __DIR__.'/../includes/Header.ini.php';
    $controlador = new AuthController();
    $controlador->requireLogin();
    
$flyers = $viewData['flyers'] ?? [];
$filters = $viewData['filters'] ?? [];
$empresas_filtradas = $viewData['empresas_filtradas'] ?? [];
$total_resultados = $viewData['total_resultados'] ?? 0;
$tiene_filtros = $viewData['tiene_filtros'] ?? false;
$page = $viewData['page'] ?? 1;
$total_pages = $viewData['total_pages'] ?? 1;
$items_per_page = $viewData['items_per_page'] ?? 20;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Publicaciones</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/searchbar.css">
    <link rel="stylesheet" href="/assets/css/flyerSearch.css">
</head>
<body>
    <?php require_once __DIR__.'/../includes/Header.ini.php';?>

    <main>
        <div class="search-container">
            <!-- Barra de búsqueda -->
            <div class="search-header">
                <h1 class="search-title">Buscar Publicaciones</h1>
                <?php require_once __DIR__.'/../includes/searchbar.ini.php'; ?>
            </div>

            <!-- Información de resultados -->
            <?php if ($tiene_filtros): ?>
            <div class="search-info">
                <div class="results-count">
                    <strong><?php echo $total_resultados; ?></strong> 
                    <?php echo $total_resultados == 1 ? 'resultado encontrado' : 'resultados encontrados'; ?>
                    <?php if ($total_pages > 1): ?>
                        - Página <strong><?php echo $page; ?></strong> de <strong><?php echo $total_pages; ?></strong>
                    <?php endif; ?>
                </div>
                
                <button class="clear-filters" onclick="window.location.href='/index.php?url=flyer/search'">
                    Limpiar filtros
                </button>
            </div>

            <!-- Filtros activos -->
            <div class="active-filters">
                <?php foreach ($empresas_filtradas as $empresa): ?>
                    <span class="filter-badge empresa">
                        🏢 <?php echo htmlspecialchars($empresa['NOMBRE_EMPRESA']); ?>
                    </span>
                <?php endforeach; ?>
                
                <!-- FUTURO: Carreras -->
                <!-- FUTURO: Tipos de estudiante -->
            </div>
            <?php endif; ?>

            <!-- Grid de flyers -->
            <?php if (!empty($flyers)): ?>
            <div class="flyers-grid">
                <?php foreach ($flyers as $flyer): ?>
                <div class="flyer-card" onclick="window.location.href='/index.php?url=flyer/view&id=<?php echo $flyer['FLAYER_ID']; ?>'">
                    <div class="flyer-image-container">
                        <?php if (!empty($flyer['URL_IMAGEN'])): ?>
                            <img src="<?php echo htmlspecialchars($flyer['URL_IMAGEN']); ?>" 
                                alt="<?php echo htmlspecialchars($flyer['TITULO']); ?>" 
                                class="flyer-image"
                                loading="lazy">
                        <?php else: ?>
                            <div class="flyer-image-placeholder">📄</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flyer-content">
                        <div class="flyer-empresa">
                            <?php echo htmlspecialchars($flyer['NOMBRE_EMPRESA']); ?>
                        </div>
                        
                        <h3 class="flyer-title">
                            <?php echo htmlspecialchars($flyer['TITULO']); ?>
                        </h3>
                        
                        <div class="flyer-date">
                            <?php 
                            $fecha = $flyer['FECHA_CREACION'];
                            if ($fecha) {
                                $timestamp = strtotime($fecha);
                                echo date('d/m/Y', $timestamp);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Paginación -->
            <?php if ($total_pages > 1): ?>
            <div class="pagination-container">
                <ul class="pagination">
                    <!-- Botón Anterior -->
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <?php if ($page > 1): ?>
                            <a href="<?php echo buildPaginationUrl($page - 1, $filters); ?>" class="page-link prev">
                                ← Anterior
                            </a>
                        <?php else: ?>
                            <span class="page-link prev">← Anterior</span>
                        <?php endif; ?>
                    </li>

                    <?php
                    // Lógica de paginación tipo Google
                    $range = 2; // Mostrar 2 páginas antes y después de la actual
                    $start = max(1, $page - $range);
                    $end = min($total_pages, $page + $range);

                    // Mostrar primera página si no está en el rango
                    if ($start > 1):
                    ?>
                        <li class="page-item">
                            <a href="<?php echo buildPaginationUrl(1, $filters); ?>" class="page-link">1</a>
                        </li>
                        <?php if ($start > 2): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Páginas del rango -->
                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a href="<?php echo buildPaginationUrl($i, $filters); ?>" class="page-link">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Mostrar última página si no está en el rango -->
                    <?php if ($end < $total_pages): ?>
                        <?php if ($end < $total_pages - 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a href="<?php echo buildPaginationUrl($total_pages, $filters); ?>" class="page-link">
                                <?php echo $total_pages; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Botón Siguiente -->
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <?php if ($page < $total_pages): ?>
                            <a href="<?php echo buildPaginationUrl($page + 1, $filters); ?>" class="page-link next">
                                Siguiente →
                            </a>
                        <?php else: ?>
                            <span class="page-link next">Siguiente →</span>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <!-- Estado vacío -->
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h3>No se encontraron publicaciones</h3>
                <p>
                    <?php if ($tiene_filtros): ?>
                        Intenta ajustar los filtros de búsqueda o elimínalos para ver todas las publicaciones.
                    <?php else: ?>
                        Aún no hay publicaciones disponibles. Vuelve pronto.
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php
/**
 * Función helper para construir URLs de paginación
 */
function buildPaginationUrl($page, $filters) {
    $params = ['url' => 'flyer/search', 'page' => $page];
    
    // Agregar filtros de empresa
    if (!empty($filters['empresa_ids'])) {
        foreach ($filters['empresa_ids'] as $id) {
            $params['tag_empresa_id'][] = $id;
        }
    }
    
    // FUTURO: Agregar filtros de carrera
    /*
    if (!empty($filters['carrera_ids'])) {
        foreach ($filters['carrera_ids'] as $id) {
            $params['tag_carrera_id'][] = $id;
        }
    }
    */
    
    // FUTURO: Agregar filtros de tipo
    /*
    if (!empty($filters['tipo_ids'])) {
        foreach ($filters['tipo_ids'] as $id) {
            $params['tag_tipo_estudiante_id'][] = $id;
        }
    }
    */
    
    return '/index.php?' . http_build_query($params);
}
?>