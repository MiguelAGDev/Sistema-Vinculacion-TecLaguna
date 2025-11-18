<?php
class PurifierService {

    private $purifier;

    public function __construct() {
        // Configuración por defecto (permite HTML básico)
        $config = \HTMLPurifier_Config::createDefault();
        
        // Opcional: Configuración específica, si quieres permitir más o menos etiquetas
        // Ejemplo: forzar UTF-8 y permitir un target blank seguro en enlaces
        $config->set('Core.Encoding', 'UTF-8'); 
        // $config->set('Attr.TargetBlank', true); 
        $config->set('HTML.Allowed', 'p,b,i,u,h1,h2,h3,h4,h5,h6,ul,ol,li,a[href|title|target],img[src|alt|width|height]');
        
        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Limpia y sanitiza una cadena HTML para prevenir XSS.
     * @param string $dirtyHtml El contenido HTML no confiable (del editor).
     * @return string El contenido HTML purificado y seguro.
     */
    public function getPurifiedHtml(string $dirtyHtml): string {
        return $this->purifier->purify($dirtyHtml);
    }
}
?>