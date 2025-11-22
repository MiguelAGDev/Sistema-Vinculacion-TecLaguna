<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderación de Publicaciones</title>

    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/moderacion.css">
</head>

<body>

<?php 
    session_start();
    require __DIR__ . '/../includes/header.ini.php';
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../controllers/FlyerController.php';

    $controller = new FlyerController($conn);
    $data = $controller->handleRequest();

    $flyers = $data['flyers'];
    $flyerSeleccionado = $data['flyerSeleccionado'];
    $totalPendientes = $data['totalPendientes'];
?>

<main>
    <section class="form-container moderacion-container">
        <h1>📋 Moderación de Publicaciones</h1>
        <p class="section-subtitle">Revisa y gestiona las publicaciones enviadas</p>

        <div class="moderacion-layout">
            <!-- PANEL IZQUIERDO: Lista de publicaciones -->
            <fieldset class="form-section sidebar-section">
                <legend>📑 Publicaciones Pendientes</legend>
                <small class="pending-count"><?= $totalPendientes ?> elementos por revisar</small>

                <div class="publications-list">
                    <?php if (empty($flyers)): ?>
                        <div class="empty-state">
                            <p>✅ No hay publicaciones pendientes</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($flyers as $flyer): ?>
                            <a href="?id=<?= $flyer['FLAYER_ID'] ?>" 
                               class="publication-card <?= ($flyerSeleccionado && $flyerSeleccionado['FLAYER_ID'] == $flyer['FLAYER_ID']) ? 'active' : '' ?>">
                                <div class="card-image">
                                    <img src="<?= htmlspecialchars($flyer['URL_IMAGEN']) ?>" 
                                         alt="<?= htmlspecialchars($flyer['TITULO']) ?>">
                                </div>
                                <div class="card-info">
                                    <h3><?= htmlspecialchars($flyer['TITULO']) ?></h3>
                                    <p class="author">por <?= htmlspecialchars($flyer['NOMBRE_EMPRESA']) ?></p>
                                    <span class="category-tag">🏢 Empresa</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </fieldset>

            <!-- PANEL DERECHO: Detalle de publicación -->
            <fieldset class="form-section detail-section">
                <legend>👁️ Vista Previa</legend>

                <?php if ($flyerSeleccionado): ?>
                    <!-- Encabezado del detalle -->
                    <div class="detail-header">
                        <div class="detail-title-row">
                            <h2><?= htmlspecialchars($flyerSeleccionado['TITULO']) ?></h2>
                            <span class="status-badge pending">⏳ Pendiente</span>
                        </div>

                        <!-- Meta información -->
                        <div class="detail-meta">
                            <span>🏢 <?= htmlspecialchars($flyerSeleccionado['NOMBRE_EMPRESA']) ?></span>
                            <span class="separator">•</span>
                            <span>🎓 Carrera</span>
                            <span class="separator">•</span>
                            <span>📅 <?= date('d/m/Y', strtotime($flyerSeleccionado['FECHA_CREACION'])) ?></span>
                        </div>

                        <!-- Botones de acción -->
                        <div class="form-actions detail-actions">
                            <a href="?action=aprobar&id=<?= $flyerSeleccionado['FLAYER_ID'] ?>" 
                               class="btn btn-primary btn-approve">
                                ✅ Aprobar
                            </a>
                            <a href="?action=rechazar&id=<?= $flyerSeleccionado['FLAYER_ID'] ?>" 
                               class="btn btn-secondary btn-reject">
                                ❌ Rechazar
                            </a>
                            <button type="button" class="btn btn-secondary btn-delete" title="Eliminar permanentemente">
                                🗑️ Eliminar
                            </button>
                        </div>
                    </div>

                    <!-- Imagen principal -->
                    <div class="detail-image">
                        <img src="<?= htmlspecialchars($flyerSeleccionado['URL_IMAGEN']) ?>" 
                             alt="<?= htmlspecialchars($flyerSeleccionado['TITULO']) ?>">
                    </div>

                    <!-- Descripción -->
                    <div class="form-group">
                        <label>📝 Descripción</label>
                        <div class="detail-description">
                            <?= $flyerSeleccionado['DESCRIPCION'] ?>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="empty-state">
                        <p>👈 Selecciona una publicación para ver sus detalles</p>
                    </div>
                <?php endif; ?>
            </fieldset>
        </div>

        <div class="form-notice">
            <small>
                📋 Las publicaciones aprobadas serán visibles para todos los usuarios.<br>
                Las publicaciones rechazadas serán ocultadas del panel de moderación.
            </small>
        </div>
    </section>
</main>

<?php if (isset($_SESSION['mensaje'])): ?>
    <div class="toast <?= $_SESSION['tipo_mensaje'] ?>">
        <?= $_SESSION['mensaje'] ?>
    </div>
    <?php unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']); ?>
<?php endif; ?>

<?php 
    require __DIR__ . '/../includes/footer.ini.php';
?>

</body>
</html>