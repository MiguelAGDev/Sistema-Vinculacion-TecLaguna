<?php
/**
 * VISTA: flyerDetailView.php
 * Vista de detalle de un flyer
 */

$flyer = $viewData['flyer'] ?? null;
$carreras = $viewData['carreras'] ?? [];
$tipos_estudiante = $viewData['tipos_estudiante'] ?? [];
$recent_flyers = $viewData['recent_flyers'] ?? [];

// Si no hay flyer, redirigir
if (!$flyer) {
    header('Location: /index.php?url=flyer/search');
    exit;
}

require_once __DIR__.'/../includes/Header.ini.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($flyer['TITULO']); ?> - Publicaciones</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/flyerDetail.css">
    
    <!-- Meta tags para compartir en redes sociales -->
    <meta property="og:title" content="<?php echo htmlspecialchars($flyer['TITULO']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars(mb_substr(strip_tags($flyer['DESCRIPCION']), 0, 150)); ?>...">
    <?php if (!empty($flyer['URL_IMAGEN'])): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($flyer['URL_IMAGEN']); ?>">
    <?php endif; ?>
</head>
<body>

    <div class="detail-container">
        <!-- Botón volver -->
        <a href="/index.php?url=flyer/search" class="back-button">
            Volver a búsqueda
        </a>

        <div class="detail-layout">
            <!-- Contenido principal -->
            <main class="detail-main">
                <div class="detail-header">
                    <!-- Título -->
                    <h1 class="detail-title">
                        <?php echo htmlspecialchars($flyer['TITULO']); ?>
                    </h1>

                    <!-- Empresa (como tag clickeable) -->
                    <a href="/index.php?url=flyer/search&tag_empresa_id[]=<?php echo $flyer['ID_EMPRESA']; ?>" 
                       class="detail-empresa">
                        <?php echo htmlspecialchars($flyer['NOMBRE_EMPRESA']); ?>
                    </a>

                    <!-- Tags de carreras y tipos -->
                    <div class="detail-tags">
                        <!-- Carreras (FUTURO) -->
                        <?php foreach ($carreras as $carrera): ?>
                            <a href="/index.php?url=flyer/search&tag_carrera_id[]=<?php echo $carrera['ID_CARRERA']; ?>" 
                               class="tag-item carrera">
                                🎓 <?php echo htmlspecialchars($carrera['NOMBRE_CARRERA']); ?>
                            </a>
                        <?php endforeach; ?>

                        <!-- Tipos de estudiante (FUTURO) -->
                        <?php foreach ($tipos_estudiante as $tipo): ?>
                            <a href="/index.php?url=flyer/search&tag_tipo_estudiante_id[]=<?php echo $tipo['ID_TIPO_ESTUDIANTE']; ?>" 
                               class="tag-item tipo">
                                👤 <?php echo htmlspecialchars($tipo['NOMBRE_TIPO']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- Fecha de publicación -->
                    <div class="detail-meta">
                        Publicado el 
                        <?php 
                        $fecha = $flyer['FECHA_CREACION'];
                        if ($fecha) {
                            $timestamp = strtotime($fecha);
                            echo date('d \d\e F \d\e Y', $timestamp);
                        }
                        ?>
                    </div>
                </div>

                <!-- Contenido HTML (ya sanitizado) -->
                <div class="detail-content">
                    <?php echo $flyer['DESCRIPCION']; ?>
                </div>
            </main>

            <!-- Sidebar con publicaciones recientes -->
            <aside class="detail-sidebar">
                <div class="sidebar-section">
                    <h2 class="sidebar-title">Publicaciones recientes</h2>
                    
                    <?php if (!empty($recent_flyers)): ?>
                    <div class="recent-flyers-list">
                        <?php foreach ($recent_flyers as $recent): ?>
                        <a href="/index.php?url=flyer/view&id=<?php echo $recent['FLAYER_ID']; ?>" 
                           class="recent-flyer-item">
                            <div>
                                <?php if (!empty($recent['URL_IMAGEN'])): ?>
                                    <img src="<?php echo htmlspecialchars($recent['URL_IMAGEN']); ?>" 
                                         alt="<?php echo htmlspecialchars($recent['TITULO']); ?>" 
                                         class="recent-flyer-image"
                                         loading="lazy">
                                <?php else: ?>
                                    <div class="recent-flyer-image-placeholder">📄</div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="recent-flyer-info">
                                <div class="recent-flyer-title">
                                    <?php echo htmlspecialchars($recent['TITULO']); ?>
                                </div>
                                <div class="recent-flyer-empresa">
                                    <?php echo htmlspecialchars($recent['NOMBRE_EMPRESA']); ?>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p style="color: #999; font-size: 14px; text-align: center;">
                        No hay más publicaciones disponibles
                    </p>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>

</body>
</html>