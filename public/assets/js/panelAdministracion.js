function toggleMenu(id) {
    const content = document.getElementById(id);
    const allDropdowns = document.querySelectorAll('.dropdown');
    const allButtons = document.querySelectorAll('.panel-option');
 
    

    // Cerrar todos los demás
    allDropdowns.forEach(menu => {
        if (menu.id !== id) menu.style.display = "none";
    });
    allButtons.forEach(btn => {
        if (btn.getAttribute("onclick") !== `toggleMenu('${id}')`) btn.classList.remove("active");
    });

    // Alternar el actual
    const button = document.querySelector(`.panel-option[onclick="toggleMenu('${id}')"]`);
    if (content.style.display === "block") {
        content.style.display = "none";
        button.classList.remove("active");
    } else {
        content.style.display = "block";
        button.classList.add("active");
    }
}
