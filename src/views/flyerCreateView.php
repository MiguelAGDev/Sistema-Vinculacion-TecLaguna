<?php 
// src/views/flyer_form.php
    require_once CONTROLLERS_PATH.'AuthController.php';
require_once INCLUDES_PATH.'Header.ini.php';
    $controlador = new AuthController();
    $controlador->requireLogin();
$formData = $viewData['form_data'] ?? [];
$carreras = $viewData['carreras'] ?? [];
$grupos = $viewData['grupos'] ?? [];
$mensaje = $viewData['mensaje'] ?? '';
$tipo_mensaje = $viewData['tipo_mensaje'] ?? '';

// Variables de edición
$editMode = $viewData['editMode'] ?? false;
$id = $viewData['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $editMode ? "Editar Oferta" : "Publicar Nueva Oferta"; ?></title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/flyers_form.css">
</head>
<body>
<?php require_once INCLUDES_PATH.'Header.ini.php';?>
<div class="main-container">
    <div class="form-container">
        <h1>
            <?php echo $editMode ? "✏️ Editar Oferta de Trabajo" : "📝 Publicar Nueva Oferta"; ?>
        </h1>
        <br><br>

        <!-- FORMULARIO ÚNICO -->
        <form method="POST" 
              action="index.php?url=flyer/<?php echo $editMode ? 'update&id='.$id : 'store'; ?>" 
              enctype="multipart/form-data" 
              class="flyer-form">

            <!-- INFO PUBLICACION -->
            <fieldset class="form-section">
                <legend>📢 Información de la Publicación</legend>

                <div class="form-group">
                    <label for="title">📌 Título de la Oferta *</label>
                    <input 
                        type="text" name="title" id="title"
                        maxlength="200" required
                        class="form-control"
                        value="<?php echo htmlspecialchars($formData['title'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="abstract">📝 Descripción del Puesto *</label>
                    <textarea 
                        name="abstract" 
                        id="abstract" 
                        class="tinymce-editor"><?php echo htmlspecialchars($formData['abstract'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="career">🎓 Carrera de Interés *</label>
                    <select id="career" name="career" required class="form-control">
                        <option value="" disabled selected>Selecciona...</option>
                        <?php foreach ($carreras as $codigo => $nombre): ?>
                            <option value="<?= $codigo ?>" 
                                <?= (($formData['career'] ?? '') === $codigo) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nombre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </fieldset>

            <!-- INFO ADICIONAL -->
            <fieldset class="form-section">
                <legend>ℹ️ Información Adicional</legend>

                <div class="form-group">
                    <label for="group">👥 Tipo de Trabajador *</label>
                    <select id="group" name="group" required class="form-control">
                        <option value="" disabled selected>Selecciona...</option>
                        <?php foreach ($grupos as $codigo => $nombre): ?>
                            <option value="<?= $codigo ?>" 
                                <?= (($formData['group'] ?? '') === $codigo) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nombre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="salary">💰 Sueldo *</label>
                    <input 
                        type="text" name="salary" id="salary" required
                        class="form-control"
                        value="<?php echo htmlspecialchars($formData['salary'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="end_date">📅 Fecha de Cierre</label>
                    <input 
                        type="date" name="end_date" id="end_date"
                        class="form-control"
                        value="<?php echo htmlspecialchars($formData['end_date'] ?? ''); ?>">
                </div>
            </fieldset>

            <!-- BOTONES -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <?= $editMode ? "💾 Guardar Cambios" : "✅ Publicar Oferta"; ?>
                </button>

                <?php if (!$editMode): ?>
                <button type="reset" class="btn btn-secondary">
                    🔄 Limpiar Formulario
                </button>
                <?php endif; ?>

                <a href="/flyer" class="btn btn-secondary">❌ Cancelar</a>
            </div>
        </form>

        <!-- NOTIFICACIÓN -->
        <?php if (!empty($mensaje)): ?>
            <div class="toast <?= $tipo_mensaje ?>">
                <?= $mensaje ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require __DIR__.'/../includes/Footer.ini.php'; ?>
<?php require __DIR__.'/../includes/tinymce_editor.php'; ?>

<script src="/assets/js/clean_img.js"></script>
<script>
setTimeout(() => {
    const t = document.querySelector('.toast');
    if (t) { t.style.opacity = 0; setTimeout(() => t.remove(), 500); }
}, 3000);
</script>

</body>
</html>
