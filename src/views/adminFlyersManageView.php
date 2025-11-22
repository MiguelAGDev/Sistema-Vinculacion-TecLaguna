<?php
/**
 * VISTA: adminFlyerManageView.php
 */

$flyers = $viewData['flyers'] ?? [];
$flyerSeleccionado = $viewData['flyerSeleccionado'] ?? null;
$totalPendientes = $viewData['totalPendientes'] ?? 0;
$mensaje = $viewData['mensaje'] ?? '';
$tipo_mensaje = $viewData['tipo_mensaje'] ?? '';

require_once __DIR__.'/../includes/Header.ini.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderación de Publicaciones</title>
    <link rel="stylesheet" href="assets/css/moderacion.css">
    <link rel="stylesheet" href="/assets/css/global.css">
</head>
<body>
    <div class="moderacion-container">
        
        <header class="headerd" style="margin-bottom: 1.5rem;">
            <div class="headerd-content">
                <h1>Moderación de Publicaciones</h1>
                <p class="section-subtitle">Revisa y gestiona las publicaciones enviadas</p>
            </div>
        </header>

        <div class="moderacion-layout">
            
            <!-- SIDEBAR: Lista de publicaciones -->
            <aside class="sidebar-section">
                <div class="sidebar-header">
                    <h2>Pendientes</h2>
                    <span class="pending-count" id="pendingCount"><?= $totalPendientes ?> por revisar</span>
                </div>
                
                <div class="publications-list" id="publicationsList">
                    <?php if (empty($flyers)): ?>
                        <div class="empty-state">
                            <p>No hay publicaciones pendientes</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($flyers as $flyer): ?>
                            <div class="publication-card <?= ($flyerSeleccionado && $flyerSeleccionado['FLAYER_ID'] == $flyer['FLAYER_ID']) ? 'active' : '' ?>"
                                 data-flyer-id="<?= $flyer['FLAYER_ID'] ?>">
                                <div class="card-image">
                                    <?php $imgSrc = !empty($flyer['URL_IMAGEN']) ? htmlspecialchars($flyer['URL_IMAGEN']) : 'assets/img/placeholder.png'; ?>
                                    <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($flyer['TITULO']) ?>">
                                </div>
                                <div class="card-info">
                                    <h3><?= htmlspecialchars($flyer['TITULO']) ?></h3>
                                    <p class="author">por <?= htmlspecialchars($flyer['NOMBRE_EMPRESA']) ?></p>
                                    <span class="category-tag">Empresa</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </aside>

            <!-- DETAIL: Vista previa de publicación -->
            <section class="detail-section" id="detailSection" 
                     data-current-id="<?= $flyerSeleccionado ? $flyerSeleccionado['FLAYER_ID'] : '' ?>">
                <?php if ($flyerSeleccionado): ?>
                    
                    <div class="detail-header">
                        <div class="detail-title-row">
                            <h2><?= htmlspecialchars($flyerSeleccionado['TITULO']) ?></h2>
                            <span class="status-badge pending">Pendiente</span>
                        </div>

                        <div class="detail-meta">
                            <span>por <?= htmlspecialchars($flyerSeleccionado['NOMBRE_EMPRESA']) ?></span>
                            <span class="separator">•</span>
                            <span>Empresa</span>
                            <span class="separator">•</span>
                            <span><?= date('d/m/Y', strtotime($flyerSeleccionado['FECHA_CREACION'])) ?></span>
                        </div>

                        <div class="detail-actions">
                            <button type="button" class="btn btn-approve" data-action="aprobar" data-id="<?= $flyerSeleccionado['FLAYER_ID'] ?>">
                                ✓ Aprobar
                            </button>
                            <button type="button" class="btn btn-reject" data-action="rechazar" data-id="<?= $flyerSeleccionado['FLAYER_ID'] ?>">
                                ✕ Rechazar
                            </button>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="detail-description">
                        <?= $flyerSeleccionado['DESCRIPCION'] ?> 
                    </div>

                <?php else: ?>
                    <div class="empty-state">
                        <p>Selecciona una publicación para ver sus detalles</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>

    <!-- Toast para notificaciones -->
    <div class="toast" id="toast" style="display: none;"></div>
    <!-- FOOTER -->
    <?php require_once __DIR__.'/../includes/Footer.ini.php'; ?>
    <!-- JavaScript externo -->
    <script src="assets/js/moderacion.js"></script>
</body>
</html>