/**
 * Moderación de Publicaciones - JavaScript
 * /assets/js/moderacion.js
 */

document.addEventListener('DOMContentLoaded', function() {
    const API_URL = '/index.php?url=api/flyer';
    
    // Obtener el ID inicial del flyer seleccionado (si existe)
    const detailSection = document.getElementById('detailSection');
    let currentFlyerId = detailSection?.dataset.currentId || null;

    // =============================================
    // SELECCIÓN DE FLYER (AJAX)
    // =============================================
    function initCardListeners() {
        document.querySelectorAll('.publication-card').forEach(card => {
            card.addEventListener('click', function() {
                const flyerId = this.dataset.flyerId;
                
                if (flyerId == currentFlyerId) return;

                document.querySelectorAll('.publication-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                loadFlyer(flyerId);
            });
        });
    }

    // =============================================
    // CARGAR FLYER VIA AJAX
    // =============================================
    function loadFlyer(id) {
        const detailSection = document.getElementById('detailSection');
        detailSection.innerHTML = '<div class="empty-state loading"><p>Cargando...</p></div>';

        fetch(`${API_URL}&action=getFlyer&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentFlyerId = id;
                    renderDetail(data.data);
                } else {
                    showToast(data.error || 'Error al cargar', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Error de conexión', 'error');
            });
    }

    // =============================================
    // RENDERIZAR DETALLE
    // =============================================
    function renderDetail(flyer) {
        const html = `
            <div class="detail-header">
                <div class="detail-title-row">
                    <h2>${escapeHtml(flyer.TITULO)}</h2>
                    <span class="status-badge pending">Pendiente</span>
                </div>

                <div class="detail-meta">
                    <span>por ${escapeHtml(flyer.NOMBRE_EMPRESA)}</span>
                    <span class="separator">•</span>
                    <span>Empresa</span>
                    <span class="separator">•</span>
                    <span>${flyer.FECHA_FORMATEADA}</span>
                </div>

                <div class="detail-actions">
                    <button type="button" class="btn btn-approve" data-action="aprobar" data-id="${flyer.FLAYER_ID}">
                        ✓ Aprobar
                    </button>
                    <button type="button" class="btn btn-reject" data-action="rechazar" data-id="${flyer.FLAYER_ID}">
                        ✕ Rechazar
                    </button>
                </div>
            </div>

            <div class="detail-description">
                ${flyer.DESCRIPCION}
            </div>
        `;

        document.getElementById('detailSection').innerHTML = html;
        bindActionButtons();
    }

    // =============================================
    // BOTONES APROBAR / RECHAZAR
    // =============================================
    function bindActionButtons() {
        document.querySelectorAll('[data-action]').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                const id = this.dataset.id;
                const mensaje = action === 'aprobar' 
                    ? '¿Aprobar esta publicación?' 
                    : '¿Rechazar esta publicación?';

                if (confirm(mensaje)) {
                    executeAction(action, id);
                }
            });
        });
    }

    // =============================================
    // EJECUTAR ACCIÓN (APROBAR/RECHAZAR)
    // =============================================
    function executeAction(action, id) {
        fetch(`${API_URL}&action=${action}&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, action === 'aprobar' ? 'success' : 'warning');
                    
                    // Remover card
                    const card = document.querySelector(`.publication-card[data-flyer-id="${id}"]`);
                    if (card) card.remove();

                    updatePendingCount();

                    // Seleccionar siguiente o mostrar vacío
                    const nextCard = document.querySelector('.publication-card');
                    if (nextCard) {
                        nextCard.click();
                    } else {
                        document.getElementById('detailSection').innerHTML = 
                            '<div class="empty-state"><p>No hay más publicaciones pendientes</p></div>';
                        currentFlyerId = null;
                    }
                } else {
                    showToast(data.error || 'Error en la operación', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Error de conexión', 'error');
            });
    }

    // =============================================
    // ACTUALIZAR CONTADOR
    // =============================================
    function updatePendingCount() {
        const count = document.querySelectorAll('.publication-card').length;
        const counter = document.getElementById('pendingCount');
        if (counter) {
            counter.textContent = `${count} por revisar`;
        }
    }

    // =============================================
    // TOAST NOTIFICACIONES
    // =============================================
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        if (!toast) return;

        toast.textContent = message;
        toast.className = `toast ${type}`;
        toast.style.display = 'block';
        toast.style.opacity = '1';

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.style.display = 'none', 300);
        }, 3000);
    }

    // =============================================
    // HELPER: Escapar HTML
    // =============================================
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // =============================================
    // INICIALIZAR
    // =============================================
    initCardListeners();
    bindActionButtons();
});