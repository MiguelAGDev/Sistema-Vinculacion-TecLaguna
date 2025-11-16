// MAIN JS: interacción lista -> preview

document.addEventListener('DOMContentLoaded', () => {
  const offerList = document.getElementById('offer-list');
  const items = Array.from(document.querySelectorAll('.offer-item'));
  const previewCard = document.getElementById('preview-card');
  const previewEmpty = document.getElementById('preview-empty');

  // elementos dentro de preview
  const previewFlyer = document.getElementById('preview-flyer');
  const previewTitle = document.getElementById('preview-title');
  const previewCompany = document.getElementById('preview-company');
  const previewType = document.getElementById('preview-type');
  const previewDesc = document.getElementById('preview-desc');
  const previewReq = document.getElementById('preview-req');
  const previewPub = document.getElementById('preview-pub');
  const previewDead = document.getElementById('preview-dead');
  const previewContact = document.getElementById('preview-contact');
  const previewEditBtn = document.getElementById('preview-edit');
  const previewApplyBtn = document.getElementById('preview-apply');

  // seleccionar item y render preview
  function selectItem(itemEl) {
    // desactivar clase active en todos
    items.forEach(i => i.classList.remove('active'));
    itemEl.classList.add('active');

    // leer datos
    const id = itemEl.dataset.id;
    const type = itemEl.dataset.type;
    const company = itemEl.dataset.company;
    const title = itemEl.dataset.title;
    const desc = itemEl.dataset.desc;
    const req = itemEl.dataset.req;
    const pub = itemEl.dataset.pub;
    const dead = itemEl.dataset.dead;
    const contact = itemEl.dataset.contact;
    const flyer = itemEl.dataset.flyer;

    // rellenar preview
    previewTitle.textContent = title;
    previewCompany.textContent = company;
    previewType.textContent = type;
    previewDesc.textContent = desc;
    previewReq.textContent = req;
    previewPub.textContent = pub;
    previewDead.textContent = dead;
    previewContact.textContent = contact;

    // imagen (si no existe, usamos placeholder)
    previewFlyer.src = flyer || '../../assets/img/flyer-placeholder.png';
    previewFlyer.alt = `Flyer - ${title}`;

    // ajustar botones (editar lleva al link del edit de la fila)
    const editLink = itemEl.querySelector('.edit-link');
    if (editLink) {
      previewEditBtn.onclick = () => {
        // redirige a la página de edición (simulado)
        window.location.href = editLink.getAttribute('href');
      };
    } else {
      previewEditBtn.onclick = null;
    }

    // Ver postulaciones (simulado)
    previewApplyBtn.onclick = () => {
      alert(`Ver postulaciones de la oferta "${title}" (simulado).`);
    };

    // mostrar preview
    previewEmpty.hidden = true;
    previewCard.hidden = false;
  }

  // click en items
  items.forEach(item => {
    item.addEventListener('click', () => selectItem(item));
    // soporte teclado (Enter)
    item.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        selectItem(item);
      }
    });
  });

  // marcar primer item por defecto
  if (items.length) selectItem(items[0]);

  // Actualizar contador (si se modifica dinámicamente)
  const countEl = document.getElementById('list-count');
  if (countEl) countEl.textContent = items.length.toString();

  // Evitar que click en edit-link dispare la selección (navegación debería continuar)
  document.querySelectorAll('.edit-link').forEach(link => {
    link.addEventListener('click', (e) => {
      // Permitir navegación; si quieres evitarla y mostrar modal, quita esta parte
      // e.stopPropagation();
      // e.preventDefault();
      // alert('Ir a editar (simulado)'); // o abrir modal
    });
  });
});
