<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Portal Principal - Panel de Administración</title>

  <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/global.css">
  <link rel="stylesheet" href="/SISTEMA-VINCULACION-TECLAGUNA/public/assets/css/main.css">
</head>
<body>

  <!-- HEADER (NO MODIFICAR) -->
  <header>
    <img src="../../assets/img/logo/logo-tecnm.png" alt="Logo del Tecnológico" />
    <nav>
      <ul>
        <li><a href="#" class="active">Inicio</a></li>
        <li><a href="#">Residencias</a></li>
        <li><a href="#">Empleos</a></li>
        <li><a href="#">Convocatorias</a></li>
        <li><a href="#">Contacto</a></li>
      </ul>
    </nav>
  </header>

  <!-- MAIN: Vista del Admin (split view: lista izquierda + preview derecha) -->
  <main class="admin-main">
    <!-- Barra superior: acciones globales -->
    <section class="admin-actions">
      <div class="actions-left">
        <h1>Panel de Administración</h1>
      </div>
      <div class="actions-right">
        <!-- Lleva a la página donde se da de alta una nueva oferta -->
        <a href="agregar.html" class="btn-primary">+ Agregar Oferta</a>
      </div>
    </section>

    <!-- Contenedor principal: lista + preview -->
    <section class="split-container">
      <!-- Lista izquierda -->
      <aside class="list-panel" aria-label="Listado de ofertas">
        <div class="list-header">
          <h2>Ofertas (Residencias / Empleos)</h2>
          <span class="list-count" id="list-count">4</span>
        </div>

        <ul class="offer-list" id="offer-list">
          <!-- Cada li contiene data-* con la info para la preview -->
          <li class="offer-item" tabindex="0"
              data-id="1"
              data-type="Residencia"
              data-company="Tech Innovators S.A."
              data-title="Desarrollo de IoT para Manufactura"
              data-desc="Proyecto para diseñar prototipos IoT aplicados a línea de producción."
              data-req="Conocimientos en C/C++, electrónica básica, trabajo en equipo."
              data-pub="2025-01-15"
              data-dead="2025-12-01"
              data-contact="recursos@techinnovators.com"
              data-flyer="../../assets/img/flyers/flyer1.png">
            <div class="offer-left">
              <strong class="offer-title">Desarrollo de IoT para Manufactura</strong>
              <div class="offer-meta">
                <span class="badge type">Residencia</span>
                <span class="muted">Tech Innovators S.A.</span>
              </div>
              <p class="offer-short">Prototipado de sensores y gateway para línea de ensamble.</p>
            </div>
            <div class="offer-right">
              <div class="dates">
                <small>Publicado: 2025-01-15</small>
                <small>Límite: 2025-12-01</small>
              </div>
              <a class="edit-link" href="editar_empresa.html?id=1" title="Editar oferta">
                <!-- Icono lápiz (SVG) -->
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" fill="currentColor"/>
                  <path d="M20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" fill="currentColor"/>
                </svg>
              </a>
            </div>
          </li>

          <li class="offer-item" tabindex="0"
              data-id="2"
              data-type="Empleo"
              data-company="Servicios Globales"
              data-title="Desarrollador Backend Java"
              data-desc="Puesto para desarrollar servicios REST para clientes del sector financiero."
              data-req="Java, Spring Boot, APIs REST, SQL."
              data-pub="2025-02-10"
              data-dead="2025-11-30"
              data-contact="talento@serviciosglobales.com"
              data-flyer="../../assets/img/flyers/flyer2.png">
            <div class="offer-left">
              <strong class="offer-title">Desarrollador Backend Java</strong>
              <div class="offer-meta">
                <span class="badge type">Empleo</span>
                <span class="muted">Servicios Globales</span>
              </div>
              <p class="offer-short">Desarrollo y mantenimiento de APIs para plataformas bancarias.</p>
            </div>
            <div class="offer-right">
              <div class="dates">
                <small>Publicado: 2025-02-10</small>
                <small>Límite: 2025-11-30</small>
              </div>
              <a class="edit-link" href="editar_empresa.html?id=2" title="Editar oferta">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" fill="currentColor"/>
                  <path d="M20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" fill="currentColor"/>
                </svg>
              </a>
            </div>
          </li>

          <li class="offer-item" tabindex="0"
              data-id="3"
              data-type="Residencia"
              data-company="Innova Solutions"
              data-title="Sistema de Control para Energía Renovable"
              data-desc="Implementación de sistema de monitoreo para paneles solares."
              data-req="Matlab/Simulink, control automático, IoT."
              data-pub="2025-03-05"
              data-dead="2025-10-20"
              data-contact="contacto@innovasolutions.mx"
              data-flyer="../../assets/img/flyers/flyer3.png">
            <div class="offer-left">
              <strong class="offer-title">Sistema de Control para Energía Renovable</strong>
              <div class="offer-meta">
                <span class="badge type">Residencia</span>
                <span class="muted">Innova Solutions</span>
              </div>
              <p class="offer-short">Diseño y validación de controladores para paneles solares.</p>
            </div>
            <div class="offer-right">
              <div class="dates">
                <small>Publicado: 2025-03-05</small>
                <small>Límite: 2025-10-20</small>
              </div>
              <a class="edit-link" href="editar_empresa.html?id=3" title="Editar oferta">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" fill="currentColor"/>
                  <path d="M20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" fill="currentColor"/>
                </svg>
              </a>
            </div>
          </li>

          <li class="offer-item" tabindex="0"
              data-id="4"
              data-type="Empleo"
              data-company="Futuro Digital"
              data-title="Analista de Datos Junior"
              data-desc="Responsable de procesamiento y visualización de datos para clientes."
              data-req="SQL, Python, visualización (PowerBI/Metabase)."
              data-pub="2025-04-12"
              data-dead="2025-12-31"
              data-contact="rrhh@futurodigital.mx"
              data-flyer="../../assets/img/flyers/flyer4.png">
            <div class="offer-left">
              <strong class="offer-title">Analista de Datos Junior</strong>
              <div class="offer-meta">
                <span class="badge type">Empleo</span>
                <span class="muted">Futuro Digital</span>
              </div>
              <p class="offer-short">Procesamiento y dashboards para proyectos de marketing.</p>
            </div>
            <div class="offer-right">
              <div class="dates">
                <small>Publicado: 2025-04-12</small>
                <small>Límite: 2025-12-31</small>
              </div>
              <a class="edit-link" href="editar_empresa.html?id=4" title="Editar oferta">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" fill="currentColor"/>
                  <path d="M20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" fill="currentColor"/>
                </svg>
              </a>
            </div>
          </li>
        </ul>
      </aside>

      <!-- Preview derecha -->
      <section class="preview-panel" id="preview-panel" aria-live="polite">
        <div class="preview-empty" id="preview-empty">
          <p>Selecciona una oferta en la lista para ver más detalles.</p>
        </div>

        <article class="preview-card" id="preview-card" hidden>
          <div class="preview-top">
            <img id="preview-flyer" src="" alt="Flyer de la oferta" class="preview-flyer" />
            <div class="preview-header">
              <h3 id="preview-title">Título de la oferta</h3>
              <div class="preview-company" id="preview-company">Empresa</div>
              <div class="preview-type" id="preview-type">Tipo</div>
            </div>
          </div>

          <div class="preview-body">
            <h4>Descripción</h4>
            <p id="preview-desc">Texto descriptivo...</p>

            <h4>Requisitos</h4>
            <p id="preview-req">Listado de requisitos...</p>

            <div class="preview-dates">
              <div><strong>Publicado:</strong> <span id="preview-pub"></span></div>
              <div><strong>Fecha límite:</strong> <span id="preview-dead"></span></div>
              <div><strong>Contacto:</strong> <span id="preview-contact"></span></div>
            </div>
          </div>

          <div class="preview-actions">
            <!-- Edita la oferta (lleva a la página de edición con id) -->
            <button id="preview-edit" class="btn-secondary">Editar</button>
            <!-- Para admin sugerimos "Ver postulaciones" -->
            <button id="preview-apply" class="btn-primary">Ver postulaciones</button>
          </div>
        </article>
      </section>
    </section>
  </main>

  <!-- FOOTER (NO MODIFICAR) -->
  <footer>
    <p>&copy; 2025 Instituto Tecnológico de La Laguna. Todos los derechos reservados.</p>
    <div class="redes">
      <a href="#">Facebook</a> |
      <a href="#">Twitter</a> |
      <a href="#">LinkedIn</a>
    </div>
  </footer>

  <!-- JS específico -->
  <script src="../../assets/js/main.js"></script>
</body>
</html>
