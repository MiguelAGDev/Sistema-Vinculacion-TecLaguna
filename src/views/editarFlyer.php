<?php require_once __DIR__.'/../includes/Header.ini.php';
    require_once __DIR__.'/../controllers/AuthController.php';
    require_once __DIR__.'/../includes/Header.ini.php';
    $controlador = new AuthController();
    $controlador->requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Flyer</title>
    <link rel="stylesheet" href="/assets/css/global.css">
   <link rel="stylesheet" href="/assets/css/editarFlyer.css">

</head>
<body>
<div class="editar-contenedor">

    <h2>Editar Flyer</h2>
    <p>Modifica la información y guarda los cambios.</p>

    <form method="POST" action="index.php?url=main/update" enctype="multipart/form-data" class="editar-form">

        <input type="hidden" name="id" value="<?= htmlspecialchars($viewData['flyer']['FLAYER_ID']) ?>">

        <label>Título</label>
        <input type="text" 
               name="titulo"
               required
               value="<?= htmlspecialchars($viewData['flyer']['TITULO']) ?>">
<!--Hola amiga, comprame pay-->
        <!--<label>Descripción</label>
        <textarea name="descripcion" required rows="5"><?= htmlspecialchars($viewData['flyer']['DESCRIPCION']) ?></textarea>
-->
        <label for="abstract">📝 Descripción del Puesto *</label>
                    <textarea 
                        name="abstract" 
                        id="abstract" 
                        class="tinymce-editor"><?php echo htmlspecialchars($formData['abstract'] ?? ''); ?></textarea>
       <!-- <label>Imagen actual</label>

            <div class="flyer-image-container">
                <?php if (!empty($viewData['flyer']['URL_IMAGEN'])): ?>
                    <img src="<?php echo htmlspecialchars($viewData['flyer']['URL_IMAGEN']); ?>" 
                        alt="<?php echo htmlspecialchars($viewData['flyer']['TITULO']); ?>" 
                        class="flyer-image"
                        loading="lazy">
                <?php else: ?>
                    <div class="flyer-image-placeholder">📄</div>
                <?php endif; ?>
            </div>-->


        <label>Cambiar imagen (opcional)</label>
        <input type="file" name="imagen" accept="image/*">

        <button type="submit" class="btn-guardar">
            Guardar cambios
        </button>

    </form>
</div>
</body>
</html>
<?php require __DIR__.'/../includes/tinymce_editor.php'; ?>
<?php require __DIR__.'/../includes/Footer.ini.php'; ?>
