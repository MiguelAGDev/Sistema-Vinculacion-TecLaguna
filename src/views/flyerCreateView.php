<?php 
// src/views/flyer_form.php

// Inicialización de variables para evitar "Undefined index" si no llegan datos
$formData = $viewData['form_data'] ?? [];
$carreras = $viewData['carreras'] ?? [];
$grupos = $viewData['grupos'] ?? [];
$mensaje = $viewData['mensaje'] ?? ''; // Por si quieres mostrar notificaciones aquí también
$tipo_mensaje = $viewData['tipo_mensaje'] ?? '';

require_once __DIR__.'/../includes/Header.ini.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Nueva Oferta</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/flyers_form.css">
</head>
<body>

    <div class="main-container"> <div class="form-container">
            <h1>📝 Publicar Nueva Oferta de Trabajo</h1>
            <br><br>
            <form method="POST" action="index.php?url=flyer/store" enctype="multipart/form-data" class="flyer-form">

                <fieldset class="form-section">
                    <legend>📢 Información de la Publicación</legend>

                    <div class="form-group">
                        <label for="title">
                            📌 Título de la Oferta *
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            placeholder="Ej: Desarrollador Full Stack - Proyecto Enterprise"
                            maxlength="200" 
                            required
                            class="form-control"
                            value="<?php echo htmlspecialchars($formData['title'] ?? ''); ?>">
                        <small class="form-hint">Máximo 200 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label for="abstract">
                            📝 Descripción del Puesto *
                        </label>
                        <input type="file" id="image-upload-trigger" accept="image/*" style="display: none;">
                        
                        <textarea 
                            name="abstract" 
                            id="abstract" 
                            class="tinymce-editor"
                            placeholder="Describe las responsabilidades, requisitos y beneficios del puesto..."><?php echo htmlspecialchars($formData['abstract'] ?? ''); ?></textarea>
                        <small class="form-hint">Usa el editor para dar formato a tu texto, para agregar imagenes solo pegalas o arrastralas al editor.</small>
                    </div>

                    <div class="form-group">
                        <label for="career">
                            🎓 Carrera de Interés *
                        </label>
                        <select id="career" name="career" required class="form-control">
                            <option value="" disabled <?php echo empty($formData['career']) ? 'selected' : ''; ?>>
                                Selecciona una carrera...
                            </option>
                            <?php foreach ($carreras as $codigo => $nombre): ?>
                                <option 
                                    value="<?php echo $codigo; ?>"
                                    <?php echo (($formData['career'] ?? '') === $codigo) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </fieldset>

                <fieldset class="form-section">
                    <legend>ℹ️ Información Adicional</legend>

                    <div class="form-group">
                        <label for="group">
                            👥 Tipo de Trabajador *
                        </label>
                        <select id="group" name="group" required class="form-control">
                            <option value="" disabled <?php echo empty($formData['group']) ? 'selected' : ''; ?>>
                                Selecciona una opción...
                            </option>
                            <?php foreach ($grupos as $codigo => $nombre): ?>
                                <option 
                                    value="<?php echo $codigo; ?>"
                                    <?php echo (($formData['group'] ?? '') === $codigo) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="salary">
                            💰 Sueldo *
                        </label>
                        <input 
                            type="text" 
                            name="salary" 
                            id="salary" 
                            placeholder="Ej: $15,000 - $20,000 MXN mensuales"
                            required
                            class="form-control"
                            value="<?php echo htmlspecialchars($formData['salary'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="end_date">
                            📅 Fecha de Cierre
                            <small>(Opcional)</small>
                        </label>
                        <input 
                            type="date" 
                            name="end_date" 
                            id="end_date"
                            class="form-control"
                            value="<?php echo htmlspecialchars($formData['end_date'] ?? ''); ?>">
                        <small class="form-hint">Dejar vacío si no hay límite de tiempo</small>
                    </div>
                </fieldset>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        ✅ Publicar Oferta
                    </button>

                    <button type="reset" class="btn btn-secondary" onclick="navigator.sendBeacon('/index.php?ruta=43/clean_img', 'cancel');">
                        🔄 Limpiar Formulario
                    </button>

                    <a 
                        href="/flyer" 
                        class="btn btn-secondary"
                        onclick="navigator.sendBeacon('/index.php?url=api/delimg', 'cancel');"
                    >
                        ❌ Cancelar
                    </a>
                </div>

                <div class="form-notice">
                    <small>
                        📋 Consulta las <a href="#">"Reglas de la Comunidad"</a> antes de publicar.
                        <br>
                        Los campos marcados con (*) son obligatorios.
                    </small>
                </div>
            </form>
        </div>

    </div> <?php if (!empty($mensaje)): ?>
        <div class="toast <?= $tipo_mensaje ?>" style="position: fixed; bottom: 20px; right: 20px; padding: 15px; background: #333; color: #fff; border-radius: 5px; z-index: 1000;">
            <?= $mensaje ?>
        </div>
        <script>
            setTimeout(function() {
                const toast = document.querySelector('.toast');
                if(toast) {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.5s';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 3000);
        </script>
    <?php endif; ?>
    <?php require_once __DIR__.'/../includes/Footer.ini.php'; ?>
    <?php require __DIR__ . '/../includes/tinymce_editor.php'; ?>
    <script src="/assets/js/clean_img.js"></script>

</body>
</html>