<!-- Cargar TinyMCE desde CDN -->
<script 
    src="https://cdn.tiny.cloud/1/3pmt7f09lj1920vk3ksjv67vfxwvglcpg2af87fed23sovu6/tinymce/6/tinymce.min.js" 
    referrerpolicy="origin">
</script>

<script>
    tinymce.init({
        selector: '.tinymce-editor',

        height: 500,
        
        menubar: false,

        plugins: [
            'advlist',          // Listas avanzadas
            'autolink',         // Auto-detectar links
            'lists',            // Listas con viñetas/números
            'link',             // Insertar enlaces
            'image',            // Insertar imágenes
            'charmap',          // Mapa de caracteres especiales
            'preview',          // Vista previa
            'anchor',           // Anclas
            'searchreplace',    // Buscar y reemplazar
            'visualblocks',     // Ver bloques HTML
            'code',             // Ver código fuente
            'fullscreen',       // Pantalla completa
            'insertdatetime',   // Insertar fecha/hora
            'media',            // Insertar video/audio
            'table',            // Tablas
            'help',             // Ayuda
            'wordcount'         // Contador de palabras
        ],

        toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | code fullscreen preview | removeformat help',

        paste_data_images: true,
        paste_as_text: false,
        // paste_word_valid_elements: 'b,strong,i,em,h1,h2,h3,h4,h5,h6,p,ul,ol,li,a[href],span,div,br',

        content_style: 
            'body { ' +
                'font-family: Arial, Helvetica, sans-serif; ' +
                'font-size: 14px; ' +
                'line-height: 1.6; ' +
                'color: #333; ' +
            '}',

        language: 'es',
        
        // Configuración de imágenes
        image_advtab: false,
        automatic_uploads: true,
        images_reuse_filename:true,
        images_upload_credentials: false,
        file_picker_callback:null,
        images_upload_handler: function (blobInfo, success, failure) {
            return new Promise((resolve, reject)=>{
                const uploadUrl = 'index.php?ruta=43/upload_img';

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                
                fetch (uploadUrl, {
                    method: 'POST',
                    body: formData
                })
                .then(response =>response.json())
                .then(result => {
                    if(result && result.location){
                        const cleanUrl = result.location.split('?')[0];
                        resolve(cleanUrl);
                    }else{
                        reject('No se obtuvo la imagen');
                    }
                })
                .catch(error=>reject(error.message));
            })
        },

        block_formats: 
            'Párrafo=p; ' +
            'Encabezado 1=h1; ' +
            'Encabezado 2=h2; ' +
            'Encabezado 3=h3; ' +
            'Encabezado 4=h4; ' +
            'Preformateado=pre',
        
        branding: false,                    // Ocultar "Powered by TinyMCE"
        promotion: false,                   // Ocultar promociones
        resize: true,                       // Permitir redimensionar
        statusbar: true,                    // Mostrar barra de estado
        elementpath: false,                 // Ocultar ruta de elementos
        
        // Validación del contenido
        extended_valid_elements: 'span[style]',

        setup: function(editor) {
            editor.on('BeforeSetContennt', function(e){
                if (e.content.match(/^<img[^>]*>$/)) {
                    const node = editor.selection.getNode();
                    
                    // Si estamos dentro de un párrafo
                    if (node.nodeName === 'P' || node.parentNode.nodeName === 'P') {
                        e.content = '</p><p style="text-align: center;">' + e.content + '</p><p>';
                    } else {
                        e.content = '<p style="text-align: center;">' + e.content + '</p>';
                    }
                }
            });

            editor.on('NodeChange', function(e) {
                const images = editor.dom.select('img');
                images.forEach(img => {
                    const src = img.getAttribute('src');
                    if (src && src.includes('?')) {
                        img.setAttribute('src', src.split('?')[0]);
                    }
                });
            });

            
            //Placeholder
            editor.on('init', function() {
                if (!editor.getContent()) {
                    editor.setContent('<p style="color: #999;">Describe las responsabilidades, requisitos y beneficios del puesto...</p>');
                    editor.selection.select(editor.getBody(), true);
                    editor.selection.collapse(false);
                }
            });
            
            // Limpiar placeholder al escribir
            editor.on('focus', function() {
                const content = editor.getContent();
                if (content.includes('color: #999;')) {
                    editor.setContent('');
                }
            });
        }
    });
</script>

<style>
    /* Estilos adicionales para el contenedor de TinyMCE */
    .tox-tinymce {
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    
    .tox-toolbar {
        background: #f7f7f7 !important;
    }
    
    .tox-statusbar {
        background: #f7f7f7 !important;
    }
</style>