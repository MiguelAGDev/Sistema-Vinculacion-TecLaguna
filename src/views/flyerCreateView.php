<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Nueva Oferta</title>

    <!-- Aquí NO se cargan los estilos del primer archivo -->
     <link rel="stylesheet" href="/assets/css/global.css">
     <link rel="stylesheet" href="/assets/css/flyers_form.css" />
</head>

<body>

<?php 
    require __DIR__ . '/../includes/header.ini.php'; 
?>

<main>
    <section class="form-container">
        <h1>📝 Publicar Nueva Oferta de Trabajo</h1>

        <form method="POST" action="/flyer/store" enctype="multipart/form-data" class="flyer-form">

            <!-- SECCIÓN: PUBLICACIÓN -->
            <fieldset class="form-section">
                <legend>📢 Información de la Publicación</legend>

                <!-- TÍTULO -->
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
                        value="<?php echo htmlspecialchars($viewData['form_data']['title'] ?? ''); ?>">
                    <small class="form-hint">Máximo 200 caracteres</small>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="form-group">
                    <label for="abstract">
                        📝 Descripción del Puesto *
                    </label>
                    <input type="file" id="image-upload-trigger" accept="image/*" style="display: none;">
                    <textarea 
                        name="abstract" 
                        id="abstract" 
                        class="tinymce-editor"
                        placeholder="Describe las responsabilidades, requisitos y beneficios del puesto..."><?php echo htmlspecialchars($viewData['form_data']['abstract'] ?? ''); ?></textarea>
                    <small class="form-hint">Puedes pegar o arrastrar imágenes al editor.</small>
                </div>

                <!-- CARRERA -->
                <div class="form-group">
                    <label for="career">
                        🎓 Carrera de Interés *
                    </label>
                    <select id="career" name="career" required class="form-control">
                        <option value="" disabled <?php echo empty($viewData['form_data']['career']) ? 'selected' : ''; ?>>
                            Selecciona una carrera...
                        </option>
                        <?php foreach ($viewData['carreras'] as $codigo => $nombre): ?>
                            <option 
                                value="<?php echo $codigo; ?>"
                                <?php echo (($viewData['form_data']['career'] ?? '') === $codigo) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($nombre); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </fieldset>

            <!-- SECCIÓN: INFORMACIÓN ADICIONAL -->
            <fieldset class="form-section">
                <legend>ℹ️ Información Adicional</legend>

                <!-- TIPO DE TRABAJADOR -->
                <div class="form-group">
                    <label for="group">
                        👥 Tipo de Trabajador *
                    </label>
                    <select id="group" name="group" required class="form-control">
                        <option value="" disabled <?php echo empty($viewData['form_data']['group']) ? 'selected' : ''; ?>>
                            Selecciona una opción...
                        </option>
                        <?php foreach ($viewData['grupos'] as $codigo => $nombre): ?>
                            <option 
                                value="<?php echo $codigo; ?>"
                                <?php echo (($viewData['form_data']['group'] ?? '') === $codigo) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($nombre); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- SUELDO -->
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
                        value="<?php echo htmlspecialchars($viewData['form_data']['salary'] ?? ''); ?>">
                </div>

                <!-- FECHA DE CIERRE -->
                <div class="form-group">
                    <label for="end_date">
                        📅 Fecha de Cierre <small>(Opcional)</small>
                    </label>
                    <input 
                        type="date" 
                        name="end_date" 
                        id="end_date"
                        class="form-control"
                        value="<?php echo htmlspecialchars($viewData['form_data']['end_date'] ?? ''); ?>">
                    <small class="form-hint">Dejar vacío si no hay fecha límite</small>
                </div>
            </fieldset>

            <!-- BOTONES -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    ✅ Publicar Oferta
                </button>
                
                <button type="reset" class="btn btn-secondary" 
                        onclick="navigator.sendBeacon('/index.php?ruta=43/clean_img', 'cancel');">
                    🔄 Limpiar Formulario
                </button>
                
                <a href="/flyer" 
                   class="btn btn-secondary"
                   onclick="navigator.sendBeacon('/index.php?ruta=43/clean_img', 'cancel');">
                    ❌ Cancelar
                </a>
            </div>

            <div class="form-notice">
                <small>
                    📋 Consulta las <a href="#">"Reglas de la Comunidad"</a> antes de publicar.<br>
                    Los campos con (*) son obligatorios.
                </small>
            </div>
        </form>
    </section>
</main>

<?php 
    require __DIR__ . '/../includes/tinymce_editor.php';
    require __DIR__ . '/../includes/footer.ini.php';
?>

<script src="/assets/js/clean_img.js"></script>

</body>
</html>
