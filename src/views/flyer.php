<?php require '../src/includes/header.php'; ?>

<?php if ($error): ?>
    <div class="error-message">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php elseif ($data): ?>
    <div class="content-wrapper">
        <div class="post-details">
            <div class="post-header">
                <h1 class="post-title"><?php echo htmlspecialchars($data['Titulo']); ?></h1>
                
                <div class="post-meta">
                    <span class="meta-item">
                        <a href="#" class="meta-link">
                            <?php echo htmlspecialchars($carreras[$data['Carrea']] ?? $data['Carrea']); ?>
                        </a>
                    </span>
                    <span class="meta-separator">•</span>
                    <span class="meta-item">
                        <a href="#" class="meta-link">
                            <?php echo htmlspecialchars($grupos[$data['Grupo']] ?? $data['Grupo']); ?>
                        </a>
                    </span>
                </div>
            </div>

            <div class="post-content">
                <div class="post-description">
                    <?php echo nl2br(htmlspecialchars($data['Descripcion'])); ?>
                </div>

                <div class="post-info">
                    </div>

                <?php if (!empty($data['Imagenes'])): ?>
                    <div class="post-images">
                        <div class="main-image">
                            <img src="assets/img_for_flyers/<?php echo htmlspecialchars($data['Imagenes'][0]); ?>" 
                                 alt="Imagen principal">
                        </div>
                        
                        </div>
                <?php endif; ?>
            </div>
        </div>

        <aside class="sidebar">
            </aside>
    </div>
<?php endif; ?>

<?php require '../src/includes/footer.php'; ?>