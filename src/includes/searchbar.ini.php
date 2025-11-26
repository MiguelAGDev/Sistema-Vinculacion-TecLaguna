<?php
// searchbar.inc.php
// Barra de búsqueda con AJAX para filtrar por tags

// Obtener tags desde GET si existen
$tags_get = isset($_GET['tag']) ? (is_array($_GET['tag']) ? $_GET['tag'] : [$_GET['tag']]) : [];
$tags_iniciales = implode(' ', array_map(function($tag) {
    return str_replace('_', ' ', $tag);
}, $tags_get));

// Convertir tags GET a JSON para JavaScript
$tags_get_json = json_encode($tags_get);
?>
<!-- <script src="/assets/js/searchbar.js"><script> -->

<div class="searchbar-container">
    <form id="searchbar-form" method="GET" action="/index.php">
        <input type="hidden" name="url" value="flyer/search">
        <div class="searchbar-input-wrapper">
            <input 
                type="text" 
                id="searchbar-input" 
                name="search_query"
                class="searchbar-input" 
                placeholder="Buscar por tags (Ej: Tecnologico_Laguna Sistemas_Computacionales)..."
                value="<?php echo htmlspecialchars($tags_iniciales); ?>"
                autocomplete="off"
            >
            <button type="submit" class="searchbar-button">🔍</button>
            <div id="searchbar-suggestions" class="searchbar-suggestions"></div>
        </div>
        
        <!-- Campos ocultos para las tags seleccionadas -->
        <div id="hidden-tags-container"></div>
    </form>
    
    <div id="searchbar-selected-tags" class="searchbar-selected-tags"></div>
</div>

<script>
    // Pasar datos PHP a JavaScript
    window.searchbarTagsIniciales = <?php echo $tags_get_json; ?>;
</script>

<script src="/assets/js/searchbar.js"></script>