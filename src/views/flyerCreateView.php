<?php 
// src/views/flyer_form.php
require __DIR__ . '/../includes/header.php'; 
?>

<div class="form-container">
    <h1>📝 Publicar Nueva Oferta de Trabajo</h1>

    <!-- FORMULARIO -->
    <form method="POST" action="/flyer/store" enctype="multipart/form-data" class="flyer-form">
        
        <!-- SECCIÓN: PUBLICACIÓN -->
        <fieldset class="form-section">
            <legend>📢 Información de la Publicación</legend>

            <!-- PORTADA -->
            <div class="form-group">
                <label for="imagen">
                    📸 Imágenes de Portada
                    <small>(Opcional - máx. 5 imágenes)</small>
                </label>
                <input 
                    type="file" 
                    name="imagen[]" 
                    id="imagen" 
                    accept=".jpg,.jpeg,.png,.gif,.webp"
                    multiple
                    class="form-control">
                <small class="form-hint">Formatos permitidos: JPG, PNG, GIF, WEBP</small>
            </div>

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

            <!-- DESCRIPCIÓN CON TINYMCE -->
            <div class="form-group">
                <label for="abstract">
                    📝 Descripción del Puesto *
                </label>
                <textarea 
                    name="abstract" 
                    id="abstract" 
                    class="tinymce-editor"
                    placeholder="Describe las responsabilidades, requisitos y beneficios del puesto..."><?php echo htmlspecialchars($viewData['form_data']['abstract'] ?? ''); ?></textarea>
                <small class="form-hint">Usa el editor para dar formato a tu texto</small>
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

            <!-- NOMBRE DE EMPRESA -->
            <div class="form-group">
                <label for="bus_name">
                    🏢 Nombre de la Empresa *
                </label>
                <input 
                    type="text" 
                    name="bus_name" 
                    id="bus_name" 
                    placeholder="Ej: TechCorp Solutions"
                    maxlength="100" 
                    required
                    class="form-control"
                    value="<?php echo htmlspecialchars($viewData['form_data']['bus_name'] ?? ''); ?>">
            </div>

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
                    📅 Fecha de Cierre
                    <small>(Opcional)</small>
                </label>
                <input 
                    type="date" 
                    name="end_date" 
                    id="end_date"
                    class="form-control"
                    value="<?php echo htmlspecialchars($viewData['form_data']['end_date'] ?? ''); ?>">
                <small class="form-hint">Dejar vacío si no hay límite de tiempo</small>
            </div>
        </fieldset>

        <!-- BOTONES DE ACCIÓN -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                ✅ Publicar Oferta
            </button>
            
            <button type="reset" class="btn btn-secondary">
                🔄 Limpiar Formulario
            </button>
            
            <a href="/flyer" class="btn btn-secondary">
                ❌ Cancelar
            </a>
        </div>

        <!-- AVISO -->
        <div class="form-notice">
            <small>
                📋 Consulta las <a href="#">"Reglas de la Comunidad"</a> antes de publicar.
                <br>
                Los campos marcados con (*) son obligatorios.
            </small>
        </div>
    </form>
</div>

<!-- INCLUIR TINYMCE -->
<?php require __DIR__ . '/../includes/tinymce_editor.php'; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>