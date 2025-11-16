<?php 
// src/views/flyer_detail.php
require __DIR__ . '/../includes/header.php'; 
?>

<div class="content-wrapper">
    <?php if (!empty($viewData['error'])): ?>
        <!-- MENSAJE DE ERROR -->
        <div class="error-message">
            <h2>⚠️ Error al cargar el flyer</h2>
            <p><?php echo htmlspecialchars($viewData['error']); ?></p>
            <a href="/flyer">← Volver a la lista de ofertas</a>
        </div>

    <?php elseif ($viewData['flyer']): ?>
        <?php $flyer = $viewData['flyer']; ?>
        
        <!-- DETALLES DEL FLYER -->
        <div class="flyer-container">
            <!-- ENCABEZADO -->
            <header class="flyer-header">
                <h1><?php echo htmlspecialchars($flyer['Titulo']); ?></h1>
                
                <div class="flyer-meta">
                    <span class="badge badge-carrera">
                        <?php echo htmlspecialchars($viewData['model']->getCarreraName($flyer['Carrera'])); ?>
                    </span>
                    <span class="badge badge-grupo">
                        <?php echo htmlspecialchars($viewData['model']->getGrupoName($flyer['Grupo'])); ?>
                    </span>
                </div>
            </header>

            <!-- IMÁGENES -->
            <?php if (!empty($flyer['Imagenes'])): ?>
                <div class="flyer-images">
                    <img 
                        src="/assets/img_for_flyers/<?php echo htmlspecialchars($flyer['Imagenes'][0]); ?>" 
                        alt="<?php echo htmlspecialchars($flyer['Titulo']); ?>"
                        class="main-image">
                    
                    <?php if (count($flyer['Imagenes']) > 1): ?>
                        <div class="image-gallery">
                            <?php foreach (array_slice($flyer['Imagenes'], 1) as $imagen): ?>
                                <img 
                                    src="/assets/img_for_flyers/<?php echo htmlspecialchars($imagen); ?>" 
                                    alt="Imagen adicional"
                                    class="gallery-image">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- INFORMACIÓN PRINCIPAL -->
            <section class="flyer-info">
                <h2>📋 Información de la oferta</h2>
                
                <table class="info-table">
                    <tr>
                        <th>🏢 Empresa:</th>
                        <td><?php echo htmlspecialchars($flyer['Empresa']); ?></td>
                    </tr>
                    <tr>
                        <th>💰 Sueldo:</th>
                        <td><?php echo htmlspecialchars($flyer['Sueldo']); ?></td>
                    </tr>
                    <tr>
                        <th>📅 Fecha de publicación:</th>
                        <td><?php echo $viewData['model']->formatDate($flyer['Fecha_inicio']); ?></td>
                    </tr>
                    <tr>
                        <th>⏰ Fecha límite:</th>
                        <td><?php echo $viewData['model']->formatDate($flyer['Fecha_fin']); ?></td>
                    </tr>
                </table>
            </section>

            <!-- DESCRIPCIÓN -->
            <section class="flyer-description">
                <h2>📝 Descripción del puesto</h2>
                <div class="description-content">
                    <?php echo nl2br(htmlspecialchars($flyer['Descripcion'])); ?>
                </div>
            </section>

            <!-- BOTONES DE ACCIÓN -->
            <div class="flyer-actions">
                <button 
                    type="button" 
                    class="btn btn-primary"
                    onclick="alert('Funcionalidad de postulación próximamente')">
                    📤 Postularme a esta oferta
                </button>
                
                <button 
                    type="button" 
                    class="btn btn-secondary"
                    onclick="window.print()">
                    🖨️ Imprimir
                </button>
                
                <button 
                    type="button" 
                    class="btn btn-secondary"
                    onclick="if(navigator.share) { navigator.share({title: '<?php echo htmlspecialchars($flyer['Titulo']); ?>', url: window.location.href}) }">
                    📱 Compartir
                </button>
            </div>
        </div>

    <?php else: ?>
        <!-- NO HAY DATOS -->
        <div class="no-data-message">
            <h2>😕 No se encontró información</h2>
            <p>El flyer que buscas no existe o no está disponible.</p>
            <a href="/flyer">← Volver a la lista de ofertas</a>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>