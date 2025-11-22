<?php
/**
 * VISTA: adminFlyerManageView.php
 */

$flyers = $viewData['flyers'] ?? [];
$flyerSeleccionado = $viewData['flyerSeleccionado'] ?? null;
$totalPendientes = $viewData['totalPendientes'] ?? 0;
$mensaje = $viewData['mensaje'] ?? '';
$tipo_mensaje = $viewData['tipo_mensaje'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderación de Publicaciones</title>
    <link rel="stylesheet" href="assets/css/moderacion.css">
</head>
<body>
    <div class="moderacion-container">
        
        <header class="header" style="margin-bottom: 1.5rem;">
            <div class="header-content">
                <h1>Moderación de Publicaciones</h1>
                <p class="section-subtitle">Revisa y gestiona las publicaciones enviadas</p>
            </div>
        </header>

        <div class="moderacion-layout">
            
            <!-- SIDEBAR: Lista de publicaciones -->
            <aside class="sidebar-section">
                <div class="sidebar-header">
                    <h2>Pendientes</h2>
                    <span class="pending-count"><?= $totalPendientes ?> por revisar</span>
                </div>
                
                <div class="publications-list">
                    <?php if (empty($flyers)): ?>
                        <div class="empty-state">
                            <p>No hay publicaciones pendientes</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($flyers as $flyer): ?>
                            <a href="?id=<?= $flyer['FLAYER_ID'] ?>" 
                               class="publication-card <?= ($flyerSeleccionado && $flyerSeleccionado['FLAYER_ID'] == $flyer['FLAYER_ID']) ? 'active' : '' ?>">
                                <div class="card-image">
                                    <?php $imgSrc = !empty($flyer['URL_IMAGEN']) ? htmlspecialchars($flyer['URL_IMAGEN']) : 'assets/img/placeholder.png'; ?>
                                    <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($flyer['TITULO']) ?>">
                                </div>
                                <div class="card-info">
                                    <h3><?= htmlspecialchars($flyer['TITULO']) ?></h3>
                                    <p class="author">por <?= htmlspecialchars($flyer['NOMBRE_EMPRESA']) ?></p>
                                    <span class="category-tag">Empresa</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </aside>

            <!-- DETAIL: Vista previa de publicación -->
            <section class="detail-section">
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
                            <a href="?action=aprobar&id=<?= $flyerSeleccionado['FLAYER_ID'] ?>" class="btn btn-approve">
                                ✓ Aprobar
                            </a>
                            <a href="?action=rechazar&id=<?= $flyerSeleccionado['FLAYER_ID'] ?>" class="btn btn-reject">
                                ✕ Rechazar
                            </a>
                        </div>
                    </div>

                    <!-- Descripción (contiene las imágenes embebidas) -->
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

    <?php if (!empty($mensaje)): ?>
        <div class="toast <?= $tipo_mensaje ?>">
            <?= $mensaje ?>
        </div>
        <script>
            setTimeout(function() {
                const toast = document.querySelector('.toast');
                if(toast) {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 3000);
        </script>
    <?php endif; ?>
</body>
</html>